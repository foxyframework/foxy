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

if($app->getVar('layout') == 'admin') {
    $app->addScript($config->site.'/bower_components/jquery-validation/jquery.validate.min.js');
    $app->addScript($config->site.'/bower_components/datatables/media/js/jquery.dataTables.min.js');
    $app->addScript($config->site.'/bower_components/datatables/media/js/dataTables.bootstrap4.min.js');
    $app->addScript($config->site.'/bower_components/datatables/media/js/tables-datatable.js');
    $app->addStyleSheet($config->site.'/bower_components/datatables/media/css/dataTables.bootstrap4.min.css');
    $app->addScript('bower_components/trumbowyg/dist/trumbowyg.min.js');
    $app->addStylesheet('bower_components/trumbowyg/dist/ui/trumbowyg.min.css');
}