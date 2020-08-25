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
	    database::query('SELECT * FROM `#_menu` WHERE inMenu = 1 ORDER BY ordering ASC');

		return database::fetchObjectList();
    }
    
    public static function getMenuModalItems() 
	{
	    database::query('SELECT * FROM `#_menu` WHERE type = 1 AND inMenu = 1 ORDER BY ordering ASC');

		return database::fetchObjectList();
    }
}
