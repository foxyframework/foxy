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

    /**
     * Method to upload user picture
    **/
    function upload()
    {
		if (!empty($_FILES)) {

			$dir 		= application::getVar('dir', '');
			$resize 	= application::getVar('resize', 0);
            $watermark 	= application::getVar('watermark', 0);
            $rename 	= application::getVar('rename', 1);

            if($dir != '') { 
                $targetPath = FOXY_ASSETS.DS.'img'.DS.$dir.DS;
            } else {
                $targetPath = FOXY_ASSETS.DS.'img'.DS;
            }

			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            $tempFile = $_FILES['file']['tmp_name'];

            if($rename == 1) {
                $filename 	= 'foxy_'.time().'_'.$_FILES['file']['name'];
            } else {
                $filename 	= $_FILES['file']['name'];
            }
            
			if($resize == 1) {

				$this->resize($watermark, $dir, $rename);

			} else {

				$targetFile =  $targetPath. $filename;
				move_uploaded_file($tempFile, $targetFile);
			}

      	}
	}
	
	/**
	 * Image resize
	 * @param int $width
	 * @param int $height
	 */
	public function resize($watermark=0, $dir='', $rename=1) 
	{
		$result = array();
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

		/* Get original image */
		list($w, $h) = getimagesize($_FILES['file']['tmp_name']);
		if ($w > $h) { //Landscape
			$width  = 1024;
			$height = 683;
		}
		if($w < $h) { //Portrait
			$width  = 800;
			$height = 1200;
		}
		if($w == $h) { //Square
			$width  = 800;
			$height = 800;
		}

		/* new file name */
		if($rename == 1) {
            $filename 	= 'foxy_'.time().'_'.$_FILES['file']['name'];
        } else {
            $filename 	= $_FILES['file']['name'];
        }
        
        if($dir != '') { 
			$targetPath = FOXY_ASSETS.DS.'img'.DS.$dir.DS.$filename;
		} else {
			$targetPath = FOXY_ASSETS.DS.'img'.DS.$filename;
		}

		/* read binary data from image file */
		$imgString 	= file_get_contents($_FILES['file']['tmp_name']);

		/* create image from string */
		$tmp 	= imagecreatetruecolor($width, $height);
		$image 	= imagecreatefromstring($imgString);
		imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $w, $h);
  
		if($watermark == 1) {
			$watermarkImagePath = FOXY_ASSETS.DS.'img'.DS.'watermark.png';
			$watermarkImg = imagecreatefrompng($watermarkImagePath); //watermark
  
			// Set the margins for the watermark
			$marge_right  = 10;
			$marge_bottom = 10;
	
			// Get the height/width of the watermark image
			$sx = imagesx($watermarkImg);
			$sy = imagesy($watermarkImg);
	
			// Copy the watermark image onto our photo using the margin offsets and
			// the photo width to calculate the positioning of the watermark.
			imagecopy($tmp, $watermarkImg, imagesx($tmp) - $sx - $marge_right, imagesy($tmp) - $sy - $marge_bottom, 0, 0, imagesx($watermarkImg), imagesy($watermarkImg));	
		}

		if($ext == 'png') { imagepng($tmp, $targetPath); }
		if($ext == 'jpeg' || $ext == 'jpg') { imagejpeg($tmp, $targetPath); }
		if($ext == 'gif') { imagegif($tmp, $targetPath); }
  
	  	// Save image and free memory
		imagedestroy($image);
		imagedestroy($tmp);
	}
}
