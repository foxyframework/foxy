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

class menu extends model
{
  public static function getMenuItems() 
	{
	  database::query('SELECT * FROM `#_menu` ORDER BY id ASC');

		return database::fetchObjectList();
    }
    
    /*
    * Method to save a new users into database
    *
    */
    public function saveMenuItem()
    {
        $obj = new stdClass();
        $obj->title         = $app->getVar('title');
        $obj->translation   = $app->getVar('translation');
        $obj->url           = $app->getVar('url');
        $obj->auth          = $app->getVar('auth');
        $obj->type          = $app->getVar('type');
        $obj->module        = $app->getVar('module');

        $result = database::insertRow("#_menu", $obj);

        if($result) {
          application::setMessage(language::get('FOXY_MENU_SAVE_SUCCESS'), 'success');
        } else {
          application::setMessage(language::get('FOXY_MENU_SAVE_ERROR'), 'danger');
        }
        application::redirect(config::$site.'/index.php?view=menu&layout=admin');
    }
}
