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

abstract class foxylogin {
	
	public static function execute($args) {
		
		$email    = application::getVar('email', '', 'post', 'string');
        $password = application::getVar('password', '', 'post', 'string');
        $redirect = application::getVar('return', '', 'post', 'string');
        $token    = application::getVar('token', '', 'post', 'string');

        $login_redirect = settings::get('login_redirect');

        //si un campo esta vacio abortamos...
        if($email == "" || $password == "") {
            application::setMessage(language::get('Rellena todos los campos por favor'), 'danger');
            application::redirect(config::$site.$login_redirect);
            return false;
        }

        database::query("SELECT password FROM `#_users` WHERE email = ".database::quote($email)." AND block = 0");
        $dbpass = database::loadResult();
        if(application::decryptPassword($password, $dbpass)) {

            database::query("SELECT id FROM `#_users` WHERE email = ".database::quote($email)." AND block = 0");
            if($id = database::loadResult()) {
                user::setAuth($id);

                //register session
                session::createSession();

                database::updateField('#_users', 'lastvisitDate',  application::getVar('lastvisitDate'), 'id', $id);
                application::setMessage(language::replace('FOXY_LOGIN_SUCCESS_MSG',  $user->username), 'success');
                $redirect == '' ? $authUrl = config::$site.$login_redirect : $authUrl = base64_decode($redirect);
                application::redirect($authUrl);
                return true;

            } else {
                application::setMessage(language::get('FOXY_LOGIN_ERROR_MSG'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register&layout=login'));
                return false;
            }
        } else {
            application::setMessage(language::get('Password not match'), 'danger');
            application::redirect(url::genUrl('/index.php?view=register&layout=login'));
            return false;
        }

		return true;
	}
}