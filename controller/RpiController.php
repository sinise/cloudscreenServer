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
     * This method controls what happens when you move to /overview/index in your app.
     * Shows a list of all users.
     */
    public function index()
    {
        $this->View->render('rpi/index', array(
            'macs' => RpiModel::getRpisOfUser(Session::get('user_id')))

        );
    }


    /**
     * This method controls what happens when you move to /overview/showProfile in your app.
     * Shows the (public) details of the selected user.
     * @param $user_id int id the the user
     */
    public function configRpi($mac)
    {
            $this->View->render('rpi/configRpi', array(
                'mac' => RpiModel::configRpi($mac))
            );
    }
}
