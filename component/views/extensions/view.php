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

//check if is an admin page and user is authenticated... 
if(!user::getAuth()) {
    $url = config::$site.'index.php?view=admin&layout=login&return='.base64_encode(config::$site.'index.php?view=admin');
    application::redirect($url, 'You are not allowed to view this resource', 'info');
    return false;
}

$model = application::getModel('extensions');

application::addScript(config::$site.'/bower_components/vanilla-datatables/dist/vanilla-dataTables.min.js');
application::addStyleSheet(config::$site.'/bower_components/vanilla-datatables/dist/vanilla-dataTables.min.css');
application::addScript(config::$site.'/bower_components/table-dragger/dist/table-dragger.min.js');