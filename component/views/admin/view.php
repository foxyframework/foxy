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

$app->addScript($config->site.'/assets/bower/datatables.net/js/jquery.dataTables.js');
$app->addScript($config->site.'/assets/bower/datatables.net-bs4/js/dataTables.bootstrap4.js');
$app->addScript($config->site.'/assets/bower/datatables.net-responsive/js/dataTables.responsive.min.js');
$app->addScript($config->site.'/assets/bower/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js');
$app->addScript($config->site.'/assets/bower/tables-datatable.js');
$app->addStyleSheet($config->site.'/assets/bower/datatables.net-bs4/css/dataTables.bootstrap4.css');
$app->addStyleSheet($config->site.'/assets/bower/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css');
