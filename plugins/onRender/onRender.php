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

abstract class onRender {
	
	public static function execute($args) {
		
		//redirect urls
		$url = url::selfUrl();
		
		database::query('SELECT * FROM `#_redirects` WHERE old_url = '.database::quote($url).' AND status = 1');
		$row = database::fetchObject();
		
		if($row->new_url != '') {
			header("Location: $row->new_url", true, 301);
			exit();
		}

		return true;
	}
}
