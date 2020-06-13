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

class profile extends model
{
    function saveProfile()
    {

      $db   = factory::get('database');
      $app  = factory::get('application');
      $user = factory::get('user');
      $lang = factory::get('language');

      $obj = new stdClass();
      $obj->email     = $app->getVar('email');
      if($_POST['password'] != '') {
        $obj->password  = $app->encryptPassword($app->getVar('password'));
      }
      //$obj->language  = $app->getVar('language');
      $obj->address   = $app->getVar('address');
      $obj->bio       = $app->getVar('bio');
      $obj->cargo     = $app->getVar('cargo');
      $obj->apikey    = $app->getVar('apikey');

      $result = $db->updateRow("#_users", $obj, 'id', $user->id);

      if($result) {
          $app->setMessage( $lang->get('FOXY_SETTINGS_SAVE_SUCCESS'), 'success');
      } else {
          $app->setMessage( $lang->get('FOXY_SETTINGS_SAVE_ERROR'), 'danger');
      }
      $app->redirect($config->site.'/index.php?view=profile');
    }
}
