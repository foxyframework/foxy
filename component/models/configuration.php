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

class configuration extends model
{
    /**
	 * Method to get all the settings object
	 * @return object 
    */
    public function getSettings()
    {
        database::query('SELECT params FROM `#_settings` WHERE id = 1');
        return json_decode(database::loadResult());
    }

    /**
	 * Method to save the settings object
	 * @return object 
    */
    public function saveSettings()
    {
        if($_GET['task'] != 'configuration.saveSettings')  { return false; }

        $result = database::updateField('#_settings', 'params', json_encode($_POST), 'id', 1);

        if($result) {
            $msg = language::get('FOXY_SETTINGS_SAVE_SUCCESS'); 
            $type = 'success';
        } else {
            $msg = language::get('FOXY_SETTINGS_SAVE_ERROR');
            $type = 'danger';
        }
        application::redirect(config::$site.'/index.php?view=configuration&layout=admin', $msg, $type);
    }
}
