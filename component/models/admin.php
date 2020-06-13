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

class admin extends model
{
  public function getConfig()
  {
    $db   = factory::get('database');
    $db->query('SELECT * FROM `#_settings` WHERE id = 1');
    return $db->fetchObject();
  }

  public function saveConfig()
  {
    if($_GET['task'] != 'register.saveConfig')  { return false; }

    $app  = factory::get('application');
    $db   = factory::get('database');
    $user = factory::get('user');
    $lang = factory::get('language');

    $result = $db->updateRow("#_settings", $_POST, 'id', 1);

    if($result) {
      $msg = $lang->get('CW_SETTINGS_SAVE_SUCCESS'); 
      $type = 'success';
    } else {
      $msg = $lang->get('CW_SETTINGS_SAVE_ERROR');
      $type = 'danger';
    }
    $app->redirect($config->site.'/index.php?view=admin', $msg, $type);
  }

  public function getAdminViews()
  {
    $folders = glob(FOXY_COMPONENT.DS.'views'.DS.'*' , GLOB_ONLYDIR);
    foreach($folders as $folder) {
      if(file_exists($folder.DS.'tmpl'.DS.'admin.php')) {
        if($folder != FOXY_COMPONENT.DS.'views'.DS.'admin') { //avoid the admin view
          $views[] = str_replace(FOXY_COMPONENT.DS.'views'.DS, '', $folder);
        }
      }
    }
    return $views;
  }
}
