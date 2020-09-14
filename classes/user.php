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

class User
{
    public static $id              = 0;
    public static $username        = "";
    public static $registerDate    = "";
    public static $email           = "";
    public static $lastvisitDate   = "0000-00-00 00:00:00";
    public static $level           = 0;
    public static $language        = "";
    public static $token           = "";
    public static $block           = 0;
    public static $image           = 'assets/img/nouser.png';
    public static $cargo           = '';
    public static $address         = '';
    public static $bio             = '';
    public static $template        = 'green';
    public static $apikey          = '';

    /**
     * Method to generate auth token
     * @param str $str
     * @return str
    */
    public static function genToken($str)
    {
        return bin2hex($str);
    }

    /**
     * Method to know if user exist
     * @param int $id
     * @return boolean true if owner false if not
    */
    public static function isUser($id)
    {
        database::query('SELECT * FROM `#_users` WHERE id = '.(int)$id);
        if(database::num_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Method to get the user authentication
     * @return boolean true if authenticate false if not
    */
    public static function getAuth()
    {
        if( !isset($_SESSION['FOXY_userid']) ) {
            return false;
        }
        return true;
    }

    /**
     * Method to set authentication values
     * @param id int the user id
     * @return void
    */
    public static function setAuth($id)
    {
        $_SESSION['FOXY_userid'] = $id;

        $row = self::getUserObject($id);

        session::setVar('id', $row->id);
        session::setVar('username', $row->username);
        session::setVar('registerDate', $row->registerDate);
        session::setVar('email', $row->email);
        session::setVar('lastvisitDate', $row->lastvisitDate);
        session::setVar('level', $row->level);
        session::setVar('language', $row->language);
        session::setVar('block', $row->block);
        session::setVar('image', $row->image);
        session::setVar('cargo', $row->cargo);
        session::setVar('address', $row->address);
        session::setVar('bio', $row->bio);
        session::setVar('token', $row->token);
        session::setVar('apikey', $row->apikey);
    }

    /**
     * Method to get the user object
     * @param id int the user id
     * @return object
    */
    public static function getUserObject($id)
    {
        database::query('SELECT * FROM `#_users` WHERE id = '.$id);
        $row = database::fetchObject();

        return $row;
    }
}
?>
