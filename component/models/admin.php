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

class admin extends model
{
  public function getConfig()
  {
    database::query('SELECT * FROM `#_settings` WHERE id = 1');
    return database::fetchObject();
  }

  public function saveConfig()
  {
    if($_GET['task'] != 'register.saveConfig')  { return false; }

    $result = database::updateRow("#_settings", $_POST, 'id', 1);

    if($result) {
      $msg = language::get('CW_SETTINGS_SAVE_SUCCESS'); 
      $type = 'success';
    } else {
      $msg = language::get('CW_SETTINGS_SAVE_ERROR');
      $type = 'danger';
    }
    application::redirect(config::$site.'/index.php?view=admin', $msg, $type);
  }
}
