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

$model = application::getModel('extensions');

if(application::getVar('layout') == 'admin') {

    if(!user::getAuth()) { application::redirect('index.php?view=home'); }
    application::addScript(config::$site.'/bower_components/vanilla-datatables/dist/vanilla-dataTables.min.js');
    application::addStyleSheet(config::$site.'/bower_components/vanilla-datatables/dist/vanilla-dataTables.min.css');
    application::addScript(config::$site.'/bower_components/table-dragger/dist/table-dragger.min.js');
}