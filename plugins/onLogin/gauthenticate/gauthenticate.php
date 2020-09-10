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

abstract class gauthenticate {
	
	public static function execute($args) {

        $g = new \Google\Authenticator\GoogleAuthenticator();

        if(application::getVar('otp') == '') {
            $email      = application::getVar('email', '', 'post', 'string');
            $password   = application::getVar('password', '', 'post', 'string');
            $redirect   = application::getVar('return', '', 'post', 'string');
            $token      = application::getVar('token', '', 'post', 'string');

            $login_redirect = settings::get('login_redirect');

            //si un campo esta vacio abortamos...
            if($email == "" || $password == "") {
                application::setMessage(language::get('FOXY_ALL_FIELDS_ARE_MANDATORY'), 'danger');
                application::redirect(config::$site.config::$login_redirect);
                return false;
            }

            database::query("SELECT password FROM `#_users` WHERE email = ".database::quote($email)." AND block = 0");
            $dbpass = database::loadResult();
            if(application::decryptPassword($password, $dbpass)) {
                //get the user id and store it before authenticate
                database::query("SELECT id FROM `#_users` WHERE email = ".database::quote($email)." AND block = 0");
                if($id = database::loadResult()) {
                    $session->setVar('tmpid', $id);
                }
            } else {
                application::setMessage(language::get('PFOXY_LOGIN_PASSWORD_NOT_MATCH_ERROR_MSG'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register&layout=login'));
                return false;
            }
        }

        if (session::getVar('tmpid') != '' && !user::getAuth() && application::getVar('otp') == '') {

            //generate new token and save into database...
            $secret = user::genToken(random_bytes(10));
            session::setVar('secret', $secret);
            database::updateField('#_users', 'token', $secret, 'id', session::getVar('tmpid'));
            application::redirect(url::genUrl('/index.php?view=register&layout=authcode'));

        } else {
            if(application::getVar('otp') != '') {
                $tmpId = application::getVar('tmpId'); 
                if ($g->checkCode('Foxy'.user::getToken($tmpId), application::getVar('otp'))) {   
                    
                    user::setAuth($tmpId);

                    //register session
                    session::createSession();

                    database::updateField('#_users', 'lastvisitDate',  application::getVar('lastvisitDate'), 'id', $tmpId);
                    application::setMessage(language::replace('FOXY_LOGIN_SUCCESS_MSG',  user::$username), 'success');
                    $redirect == '' ? $authUrl = config::$site.$login_redirect : $authUrl = base64_decode($redirect);
                    application::redirect($authUrl);
                    return true;

                }
            }
        }
	}
}