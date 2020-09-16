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

session_name('Foxy_'.date('d'));
session_start();
define('_Foxy', 1);
error_reporting(E_ALL ^ E_NOTICE);

date_default_timezone_set('Europe/Berlin');
define('FOXY_BASE', dirname(__FILE__) );
define('DS', DIRECTORY_SEPARATOR );

require_once(FOXY_BASE.DS.'includes/defines.php');
require_once(FOXY_CLASSES.DS.'autoloader.php');
require_once(FOXY_VENDOR.DS.'autoload.php');

//trigger plugin onRender before the app is ready...
application::trigger('onRender', array());

$code = language::getActive();
if(!isset($_COOKIE['lang'])) { setcookie('lang', $code[0]); }

//print_r($_SESSION);
//print_r(get_declared_classes());

//set error level
if(settings::get('debug') == 1) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

//render application
application::getView();
application::getTemplate();

?>
