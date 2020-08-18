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

class media extends model
{
    public function getMediaFolders($folder="")
    {
        $folder == '' ? $dir = FOXY_ASSETS.DS.'img' : $dir = FOXY_ASSETS.DS.'img'.DS.$folder;
        $sub_directories = array_map('basename', glob($dir . '/*', GLOB_ONLYDIR));
        return $sub_directories;
    }

    public function getMediaFiles($folder="")
    {
        $folder == '' ? $dir = FOXY_ASSETS.DS.'img'.DS : $dir = FOXY_ASSETS.DS.'img'.DS.$folder.DS;
        $dir = opendir($dir);
		while (false !== ($file = readdir($dir))) {
			if($file != "." && $file != "..") {
                $info = pathinfo($dir.$file);
                $allowed = array('jpg', 'png', 'gif', 'jpeg', 'mp4', 'avi', 'ogg');
                if(in_array($info['extension'], $allowed))  {
                    $ficheros[] = $file;
                }
			}
		}
        closedir($dir);
        return $ficheros;
    }
}
