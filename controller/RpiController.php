<?php

class RpiController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens when you move to /rpi/index in your app.
     * Shows a list of users rpi's.
     */
    public function index()
    {
        $this->View->render('rpi/index', array(
            'macs' => RpiModel::getRpisOfUser(Session::get('user_id')))

        );
    }


    /**
     * This method controls what happens when you move to /rpi/configRpi in your app.
     * Shows the config of rpi.
     * @param $mac string mac of the rpi
     */
    public function configRpi($mac)
    {
            $this->View->render('rpi/configRpi', array(
                'mac' => RpiModel::configRpi($mac))
            );
    }
    /**
     * Configure Rpi action Add the configuration message to the queue (table commands in db)
     * POST-request after form submit
     */
    public function configRpi_action()
    {
        $configuration_successful = RpiModel::sendConfig();
        if ($configuration_successful) {
            Redirect::to('rpi/index');
        } else {
            Redirect::to('rpi/index');
        }

    }

    /**
     * Receive status Rpi action
     * POST-request after received 
     */
    public function recvStatusRpi_action($mac)
    {
        $status_successful = RpiModel::recvStatusRpi();
        if ($status_successful) {
            $this->View->renderJSON(array(
                'mac' => RpiModel::sendCommandRpi($mac))
            );
        } else {
            Redirect::to('rpi/index');
        }

    }
}
