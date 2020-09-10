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

class Settings {

	/**
     * Method to get a settings value
	 * @param string $name The var name
	 * @param mixed $value The default value if empty
    */
	public static function get($name, $default='') {

		database::query('SELECT params FROM `#_settings` WHERE id = 1');
        $row = json_decode(database::loadResult());

        if($row->{$name} == '') { 
            return $default;
        } else {
            return $row->{$name};
        }
	}
}
