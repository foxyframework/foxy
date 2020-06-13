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

session_name('Foxy_'.date('d'));
session_start();
define('_Foxy', 1);
error_reporting(E_ALL ^ E_NOTICE);

date_default_timezone_set('Europe/Berlin');
define('FOXY_BASE', dirname(__FILE__) );
define('DS', DIRECTORY_SEPARATOR );

require_once(FOXY_BASE.DS.'includes/defines.php');
require_once(FOXY_CLASSES.DS.'factory.php');

$db      = factory::get('database');
$app     = factory::get('application');
$config  = factory::get('config');
$html    = factory::get('html');
$lang    = factory::get('language');
$session = factory::get('session');
$url     = factory::get('url');
$user    = factory::get('user');

//trigger plugin onRender before the app is ready...
$app->trigger('onRender', array());

//print_r($_SESSION);
//print_r(get_declared_classes());

//set error level
if($config->debug == 1) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

//render application
include($app->getView());
include($app->getTemplate());

?>
