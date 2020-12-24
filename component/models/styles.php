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

include('includes/model.php');

require_once FOXY_VENDOR.DS.'scssphp/scssphp/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;

class styles extends model
{
    /**
	 * Method to get all the settings object
	 * @return object 
    */
    public function getStyles()
    {
        database::query('SELECT styles FROM `#_settings` WHERE id = 1');
        return json_decode(database::loadResult());
    }

    /**
	 * Method to save the settings object
	 * @return object 
    */
    public function compile()
    {
        if($_GET['task'] != 'styles.compile')  { return false; }

        $result = database::updateField('#_settings', 'styles', json_encode($_POST), 'id', 1);

        try {
            $scss = new Compiler();
            $scss->setImportPaths('template/'.config::$template.'/css/');
            $scss->setVariables(array(
                'primary'   => $_POST['primary'],
                'secondary' => $_POST['secondary'],
                'info'      => $_POST['info'],
                'success'   => $_POST['success'],
                'warning'   => $_POST['warning'],
                'danger'    => $_POST['danger'],
                'light'     => $_POST['light'],
                'dark'      => $_POST['dark'],
            ));

            $content = $scss->compile('@import "custom.scss";');

        } catch (\Exception $e) {
            echo '';
            syslog(LOG_ERR, 'scssphp: Unable to compile content');
        }

        if($result) {
            $msg = language::get('FOXY_STYLES_SAVE_SUCCESS'); 
            $type = 'success';
        } else {
            $msg = language::get('FOXY_STYLES_SAVE_ERROR');
            $type = 'danger';
        }

        application::redirect(config::$site.'/index.php?view=styles&layout=admin&styles='.$content, $msg, $type);
    }
}
