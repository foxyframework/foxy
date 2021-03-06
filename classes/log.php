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

class Log {
    
	private $log_file, $fp;
    

	public function lfile($path) {
		self::$log_file = $path;
	}
    
	// write message to the log file
	public function lwrite($message) {
		if (!is_resource($this->fp)) {
			self::lopen();
		}
		// define script name
		$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		// define current time and suppress E_WARNING if using the system TZ settings
		// (don't forget to set the INI setting date.timezone)
		$time = @date('[d/M/Y:H:i:s]');
		// write current time, script name and message to the log file
		fwrite(self::$fp, "$time ($script_name) $message" . PHP_EOL);
	}

	// close log file (it's always a good idea to close a file when you're done with it)
	public static function lclose() {
		fclose(self::$fp);
	}

	// open log file (private method)
	private static function lopen() {
		
		$log_file_default = config::$log;
	       
		// define log file from lfile method or use previously set default
		$lfile = self::$log_file ? self::$log_file : $log_file_default;
		// open log file for writing only and place file pointer at the end of the file
		// (if the file does not exist, try to create it)
		self::$fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
	 }
}
