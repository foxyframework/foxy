<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Foxy') or die ('restricted access');

include('includes/model.php');

class users extends model
{
    /*
    * Method to save a new users into database
    *
    */
    public function saveUser()
    {
        $app  = factory::get('application');
        $db   = factory::get('database');
        $user = factory::get('user');
        $lang = factory::get('language');

        $obj = new stdClass();
        $obj->username      = $app->getVar('username');
        $obj->email         = $app->getVar('email');
        $obj->password      = $app->encryptPassword($app->getVar('password'));
        $obj->level         = $app->getVar('usergroup');
        $obj->block         = 0;
        $obj->token         = $user->genToken($obj->email);
        $obj->registerDate  = date('Y-m-d H:i:s');
        $obj->language      = 'ca-es';
        $obj->cargo         = '';
        $obj->bio           = '';
        $obj->address       = '';
        $obj->template      = 'green';
        $obj->apikey        = '';

        $result = $db->insertRow("#_users", $obj);

        if($result) {
          $app->setMessage($lang->get('FOXY_USERS_SAVE_SUCCESS'), 'success');
        } else {
          $app->setMessage($lang->get('FOXY_USERS_SAVE_ERROR'), 'danger');
        }
        $app->redirect($config->site.'/index.php?view=users&layout=admin');
    }
}
