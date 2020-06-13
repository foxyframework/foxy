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

class topmenuHelper
{
    public static function getItems() 
	{
		$db   = factory::get('database');

	    $db->query('SELECT * FROM `#_menu` ORDER BY id');

		return $db->fetchObjectList();
    }
    
    public static function getMenuModalItems() 
	{
		$db   = factory::get('database');

	    $db->query('SELECT * FROM `#_menu` WHERE type = 1 ORDER BY id ASC');

		return $db->fetchObjectList();
    }
}
