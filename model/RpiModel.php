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

        $sql = "SELECT rpiStatus.mac, rpiStatus.ip, rpiStatus.wan, rpiStatus.cpu, rpiStatus.ram, rpiStatus.url, rpiStatus.urlViaServer,
                rpiStatus.orientation, rpiStatus.lastMTransTime, rpiStatus.creatTime, userRpiAsoc.user_id FROM userRpiAsoc
                INNER JOIN rpiStatus ON rpiStatus.mac = userRpiAsoc.mac
                WHERE user_id = :user_id";
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
                FROM rpiStatus WHERE mac = :mac LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':mac' => $mac));
        $mac = $query->fetch();

       return $mac;
    }
}
