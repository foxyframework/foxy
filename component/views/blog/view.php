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

$model = application::getModel('blog');
$params = $model->getParams('blog');

if(application::getVar('layout') == 'admin') {

    if(!user::getAuth()) { application::redirect('index.php?view=home'); }
    application::addScript(config::$site.'/bower_components/jquery-validation/dist/jquery.validate.min.js');
    application::addScript(config::$site.'/bower_components/datatables/media/js/jquery.dataTables.min.js');
    application::addScript(config::$site.'/bower_components/datatables/media/js/dataTables.bootstrap4.min.js');
    application::addStyleSheet(config::$site.'/bower_components/datatables/media/css/dataTables.bootstrap4.min.css');
    application::addScript(config::$site.'/bower_components/trumbowyg/dist/trumbowyg.min.js');
    application::addStylesheet(config::$site.'/bower_components/trumbowyg/dist/ui/trumbowyg.min.css');
}