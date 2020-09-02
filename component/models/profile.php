<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    Foy Team
 * @website	    https://foxyframework.github.io/foxysite/
 *
*/

defined('_Foxy') or die ('restricted access');

include('includes/model.php');

class profile extends model
{
    function saveProfile()
    {
      $obj = new stdClass();
      $obj->email     = application::getVar('email');
      if($_POST['password'] != '') {
        $obj->password  = application::encryptPassword(application::getVar('password'));
      }
      //$obj->language  = application::getVar('language');
      $obj->address   = application::getVar('address');
      $obj->bio       = application::getVar('bio');
      $obj->cargo     = application::getVar('cargo');
      $obj->apikey    = application::getVar('apikey');

      $result = database::updateRow("#_users", $obj, 'id', user::$id);

      if($result) {
        application::setMessage( language::get('FOXY_SETTINGS_SAVE_SUCCESS'), 'success');
      } else {
        application::setMessage( language::get('FOXY_SETTINGS_SAVE_ERROR'), 'danger');
      }
      application::redirect(config::$site.'/index.php?view=profile');
    }
}
