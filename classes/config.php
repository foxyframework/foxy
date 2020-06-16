<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Foxy') or die ('restricted access');

class Config {

	public $site        = '';
	public $offline     = 0;
	public $log         = '';
	public $sitename    = 'Foxy';
	public $description = 'Small PHP Framework';
	public $email       = '';
	public $debug       = 1;
	public $driver      = 'mysql';
	public $host        = 'localhost';
	public $user        = '';
	public $pass        = '';
	public $database    = '';
	public $dbprefix    = 'foxy_';
	public $token_time  = 300;
	public $template    = 'foxy';
	public $cookie      = 30;
	public $admin_mails = 1;
	public $inactive    = 1000;
	public $login_redirect = 'index.php?view=home';
	public $show_register = 0;
	public $pagination  = 20;
	public $recaptcha   = 0;
	public $public_key  = '';
	public $secretKey   = '';
}
