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

abstract class addthis {
	
	public static function execute($args) {
        
        $code = 'ra-562a0664e3918ad1';
        application::addScript('//s7.addthis.com/js/300/addthis_widget.js#pubid='.$code);
        $html = '<div class="addthis_sharing_toolbox"></div>';
        echo $html;

    }

}