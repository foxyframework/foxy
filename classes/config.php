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

class Config {

	public static $site        = '';
	public static $log         = '';
	public static $sitename    = 'Foxy';
	public static $description = 'Small PHP Framework';
	public static $email       = '';
	public static $host        = 'localhost';
	public static $user        = '';
	public static $pass        = '';
	public static $database    = '';
	public static $dbprefix    = 'foxy_';
	public static $token_time  = 300;
	public static $template    = 'foxy';
	public static $cookie      = 30;
	public static $inactive    = 1000;
}
