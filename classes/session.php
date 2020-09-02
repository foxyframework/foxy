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

class Session {

	/**
     * Method to set a session var
	 * @param string $name The cookie name
	 * @param mixed $value The cookie value
    */
	public static function setVar($name, $value) {

		$_SESSION[$name] = $value;

	}

	/**
     * Method to get a session var
	 * @param string $name The cookie name
    */
	public static function getVar($name) {

		if (isset ( $_SESSION[$name] ) && !empty(  $_SESSION[$name]  )) {
			return $_SESSION[$name];
		} else {
			return false;
		}
	}	

	/**
     * Method to remove a session var
	 * @param string $name The cookie name
    */
	public static function deleteVar($name) {

		unset ( $_SESSION[$name] );
	}
	
	/**
     * Method to create user session vars
    */
	public static function createSession() {

		$s = new stdClass();
		$s->userid = user::$id;
		$s->ssid = session_id();
		$s->lastvisitDate = date('Y-m-d H:i:s');
		database::insertRow('#_sessions', $s);
	}

	/**
     * Method to destroy a session
    */
	public static function destroySession() {
		
		//Unset token and user data from session
		unset($_SESSION['FOXY_userid'], $_SESSION['token']);

		database::query('DELETE FROM `#_sessions` WHERE userid = '.user::$id);
		$_SESSION = array();
		session_destroy();
		
	}	

}
