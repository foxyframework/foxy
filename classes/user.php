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
    public $id              = 0;
    public $username        = "";
    public $password        = "";
    public $registerDate    = "";
    public $email           = "";
    public $lastvisitDate   = "0000-00-00 00:00:00";
    public $level           = 0;
    public $language        = "";
    public $token           = "";
    public $block           = 0;
    public $image           = 'nouser.png';
    public $cargo           = '';
    public $address         = '';
    public $bio             = '';
    public $template        = 'green';
    public $apikey          = '';

    /**
     * Constructor
    */
    public function __construct() {
        if( isset($_SESSION['FOXY_userid']) ) {

            if(isset($_SESSION['FOXY_userid'])) { $this->id = $_SESSION['FOXY_userid']; }
            $this->setAuth($this->id);
        }
    }

    /**
     * Method to generate auth token
     * @param str $str
     * @return str
    */
    public function genToken($str)
    {
        return bin2hex($str);
    }

    /**
     * Method to know if user exist
     * @param int $id
     * @return boolean true if owner false if not
    */
    public function isUser($id)
    {
        $db  = factory::get('database');
        $db->query('SELECT * FROM `#_users` WHERE id = '.(int)$id);
        if($db->num_rows() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Method to get the user authentication
     * @return boolean true if authenticate false if not
    */
    public function getAuth()
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
    public function setAuth($id)
    {
        $_SESSION['FOXY_userid'] = $id;

        $db  = factory::get('database');
        $db->query('SELECT * FROM `#_users` WHERE id = '.$id);
        $row = $db->fetchArray();

        foreach($row as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * Method to get the user object
     * @param id int the user id
     * @return object
    */
    public function getUserObject($id)
    {

        $db  = factory::get('database');
        $db->query('SELECT * FROM `#_users` WHERE id = '.$id);
        $row = $db->fetchObject();

        return $row;
    }
}
?>
