<?php

/**
 * ConfigRpiModel
 * Handles the configuration interface of the Rpi's
 */
class RpiModel
{
    /**
     * Gets an array that contains all the rpi's that the current user has access to from the database. The array's keys are the mac address of the rpi
     * @return array The mac of the users rpi's
     */
    public static function getRpisOfUser($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        if (Session::get('user_acount_type') == 3) {
            $sql = "SELECT rpiStatus.id, rpiStatus.mac, rpiStatus.ip, rpiStatus.wan, rpiStatus.cpu, rpiStatus.ram, rpiStatus.url, rpiStatus.urlViaServer,
                    rpiStatus.orientation, rpiStatus.lastMTransTime, rpiStatus.creatTime FROM rpiStatus
                    ORDER BY id DESC LIMIT 1";

        }
        else {

            $sql = "SELECT rpiStatus.id, rpiStatus.mac, rpiStatus.ip, rpiStatus.wan, rpiStatus.cpu, rpiStatus.ram, rpiStatus.url, rpiStatus.urlViaServer,
                    rpiStatus.orientation, rpiStatus.lastMTransTime, rpiStatus.creatTime, userRpiAsoc.user_id FROM userRpiAsoc
                    INNER JOIN rpiStatus ON rpiStatus.mac = userRpiAsoc.mac
                    WHERE user_id = :user_id
                    ORDER BY id DESC LIMIT 1";
        }
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $macs = array();

        foreach ($query->fetchAll() as $mac) {
            // a new object for every mac. This is eventually not really optimal when it comes
            // to performance, but it fits the view style better
     	    $macs[$mac->mac] = new stdClass();
            $macs[$mac->mac]->mac = $mac->mac;
            $macs[$mac->mac]->ip = $mac->ip;
            $macs[$mac->mac]->wan = $mac->wan;
            $macs[$mac->mac]->cpu = $mac->cpu;
            $macs[$mac->mac]->ram = $mac->ram;
            $macs[$mac->mac]->url = $mac->url;
            $macs[$mac->mac]->urlViaServer = $mac->urlViaServer;
            $macs[$mac->mac]->orientation = $mac->orientation;
            $macs[$mac->mac]->lastMTransTime = $mac->lastMTransTime;
            $macs[$mac->mac]->createTime = $mac->creatTime;
        }
        return $macs;
    }

    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return mixed The selected user's profile
     */
    public static function configRpi($mac)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT id, mac, url, urlViaServer, orientation
                FROM rpiStatus WHERE mac = :mac
                ORDER BY id DESC
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':mac' => $mac));
        $mac = $query->fetch();

       return $mac;
    }



    public static function doesConfigAlreadyExist($mac)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT mac FROM commands WHERE mac = :mac LIMIT 1");
        $query->execute(array(':mac' => $mac));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }




 public static function sendConfig()
    {
        // clean the input
        $orientation = Request::post('orientation');
        $url = Request::post('url');
        $urlViaServer = Request::post('urlViaServer');
        $command = Request::post('command');
        $mac = Request::post('mac');

        // check if there is already a config for the unit waiting to be send
        // check if there is already a config for the unit waiting to be send
        if (RpiModel::doesConfigAlreadyExist($mac)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_CONFIG_ALREADY_EXIST'));
         //   return false;
        } else {
        // write user data to database
            if (!RpiModel::writeConfigToDatabase($orientation, $url, $urlViaServer, $command, $mac)) {
                Session::add('feedback_negative', Text::get('FEEDBACK_CONFIG_NOT_WRITEN'));
            }
            else {
                Session::add('feedback_positive', Text::get('FEEDBACK_CONFIG_WRITEN'));
            }
        }
    }

    /**
     * @param $user_name
     * @param $user_password_hash
     * @param $user_email
     * @param $user_creation_timestamp
     * @param $user_activation_hash
     *
     * @return bool
     */
    public static function writeConfigToDatabase($orientation, $url, $urlViaServer, $command, $mac)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // write new config to database to be qued for sending
        $sql = "INSERT INTO commands (orientation, url, urlViaServer, command, mac)
                VALUES (:orientation, :url, :urlViaServer, :command, :mac)";
        $query = $database->prepare($sql);
        $query->execute(array(':orientation' => $orientation,
                              ':url' => $url,
                              ':urlViaServer' => $urlViaServer,
                              ':command' => $command,
                              ':mac' => $mac));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }



    public static function recvStatusRpi()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // clean the input
        $ip = Request::post('ip');
        $mac = Request::post('mac');
        $wan = Request::post('wan');
        $cpu = Request::post('cpu');
        $ram = Request::post('ram');
        $url = Request::post('url');
//        $urlViaServer = Request::post('urlViaServer');
        $orientation = Request::post('orientation');
        $lastMTransTime = Request::post('lastMTransTime');

        // write status to database

        // write status to database 
        $sql = "INSERT INTO rpiStatus (ip, mac, wan, cpu, ram, url, orientation, lastMTransTime)
                VALUES (:ip, :mac, :wan, :cpu, :ram, :url, :orientation, :lastMTransTime)";
        $query = $database->prepare($sql);
        $query->execute(array(':ip' => $ip,
                              ':mac' => $mac,
                              ':wan' => $wan,
                              ':cpu' => $cpu,
                              ':ram' => $ram,
                              ':url' => $url,
                              ':orientation' => $orientation,
                              ':lastMTransTime' => $lastMTransTime));
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }
    /**
     * Sending new configuration or commands to the RPi as a response to a received status message
     * @param int $mac The mac of the rpi
     * @return the config messages as an array
     */
    public static function sendCommandRpi($mac)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                FROM commands WHERE mac = :mac LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':mac' => $mac));
        $macs = $query->fetch();

        $query = $database->prepare("DELETE FROM commands WHERE mac = :mac");
        $query->execute(array(':mac' => $mac));
        $sql = "DELETE FROM commands WHERE mac = :mac";

       return $macs;
    }


}
