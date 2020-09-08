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

include_once('includes/model.php');

class register extends model
{
    /**
     * Method to check if email exists
    */
    public function checkEmail()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            $email      = $_GET['email'];

            database::query('select id from #_users where email = '.database::quote($email));
            if($id = database::loadResult()) {
                echo false;
            } else {
                echo true;
            }
        }
    }

    /**
     * Method to register a new user
    */
    public function register()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            //si un campo esta vacio abortamos...
            if($_POST['email'] == "" || $_POST['password'] == "" || $_POST['password2'] == "") {
                application::setMessage(language::get('Rellena todos los campos por favor'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register'));
                return false;
            }

            //check if email exists...
            database::query('select id from #_users where email = '.database::quote($_POST['email']));
            if($id = database::loadResult()) {
                application::setMessage(language::get('El email ya existe, por favor elige otro'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register'));
                return false;
            }

            if($_POST['password'] === $_POST['password2']) {

                unset($_POST['password2']);
                unset($_POST['auth_token']);

                //create user
                $_POST['username']      = $_POST['email'];
                $_POST['email']         = $_POST['email'];
                $_POST['password']      = application::encryptPassword($_POST['password']);
                $_POST['registerDate']  = date('Y-m-d H:i:s');
                $token                  = user::genToken($_POST['email']);
                $_POST['token']         = $token;
                $_POST['level']         = 2;
                $_POST['language']      = 'en-gb';
                $result = database::insertRow('#_users', $_POST);

                $lastid = database::lastId();

                if($result && $result2) {
                    //send a confirmation to the user...
                    $subject    = language::replace('FOXY_REGISTER_WELCOME_SUBJECT', config::$sitename);
                    $link       = config::$site.'/index.php?task=register.validate&token='.$token;
                    $body       = language::replace('FOXY_REGISTER_WELCOME_BODY', $_POST['username'],  config::$sitename, $link);
                    $send       = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);

                    if($send) {
                        application::setMessage(language::replace('FOXY_REGISTER_SUCCESS_MSG', config::$sitename), 'success');
                        application::redirect(url::genUrl('/index.php?view=home'));
                        exit(0);
                    } else {
                        //mostrar el link de activacion en el mensaje ya que fallo el email...
                        application::setMessage(language::replace('FOXY_REGISTER_EMAIL_ERROR_MSG', $link), 'danger');
                        application::redirect(url::genUrl('/index.php?view=register'));
                        return false;
                    }
                } else {
                    application::setMessage(language::get('FOXY_REGISTER_ERROR_MSG'), 'danger');
                    application::redirect(url::genUrl('/index.php?view=register'));
                    return false;
                }
            } else {
                application::setMessage(language::get('FOXY_REGISTER_PASSWORDS_NOT_MATCH_MSG'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register'));
                return false;
            }
        }
    }

    /**
     * Method to reset the user password
    */
    public function resendActivation()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            //send a confirmation to the user...
            $subject    = language::replace('FOXY_REGISTER_WELCOME_SUBJECT', config::$sitename);
            $link       = config::$site.'/index.php?task=register.validate&token='.user::$token;
            $body       = language::replace('FOXY_REGISTER_WELCOME_BODY', user::$username,  config::$sitename, $link);
            $send       = $this->sendMail(user::$email, user::$email, $subject, $body);

            if($send) {
                application::setMessage(language::get('FOXY_REGISTER_RESET_SUCCESS_MSG'), 'success');
                application::redirect(url::genUrl('/index.php?view=home'));
            } else {
                application::setMessage(language::get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
            }
        }
    }

    /**
     * Method to reset the user password
    */
    public function reset()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            //si un campo esta vacio abortamos...
            if($_POST['email'] == "") {
                application::setMessage(language::get('Rellena todos los campos por favor'), 'danger');
                application::redirect(url::genUrl('/index.php?view=register&layout=reset'));
                return false;
            }

            $email  = database::quote($_POST['email']);

            database::query("SELECT id FROM `#_users` WHERE email = $email AND block = 0");
            $id = database::loadResult();
            $newpassword = uniqid();
            $password = application::encryptPassword($newpassword);
            $result = database::updateField('#_users', 'password', $password, 'id', $id);
            //send email to user...
            if($result) {
                //send a confirmation to the user...
                $subject  = language::replace('FOXY_REGISTER_RESET_SUBJECT', config::$sitename);
                $body     = language::replace('FOXY_REGISTER_RESET_BODY', $_POST['email'], config::$sitename, $newpassword);
                $send     = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);

                if($send) {
                    application::setMessage(language::get('FOXY_REGISTER_RESET_SUCCESS_MSG'), 'success');
                    application::redirect(url::genUrl('/index.php?view=home'));
                } else {
                    application::setMessage(language::get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
                }
            } else {
                application::setMessage(language::get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
            }
        }
    }

    /**
     * Method to save the user profile
    */
    public function saveProfile()
    {
      $obj = new stdClass();
      $obj->email     = application::getVar('email');
      if($_POST['password'] != '') {
        $obj->password  = application::encryptPassword(application::getVar('password'));
      }
      //$obj->language  = application::getVar('language');
      $obj->address   = application::getVar('address');
      $obj->bio       = application::getVar('bio');
      $obj->cargo     = application::getVar('cargo');
      $obj->apikey    = application::getVar('apikey');

      $result = database::updateRow("#_users", $obj, 'id', user::$id);

      if($result) {
        application::setMessage( language::get('FOXY_SETTINGS_SAVE_SUCCESS'), 'success');
      } else {
        application::setMessage( language::get('FOXY_SETTINGS_SAVE_ERROR'), 'danger');
      }
      application::redirect(config::$site.'/index.php?view=register&layout=profile');
    }

    /**
     * Method to login into the application
    */
    public function twosteps()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            $g = new \Google\Authenticator\GoogleAuthenticator();

            if(application::getVar('otp') == '') {
                $email      = application::getVar('email', '', 'post', 'string');
                $password   = application::getVar('password', '', 'post', 'string');
                $redirect   = application::getVar('return', '', 'post', 'string');
                $token      = application::getVar('token', '', 'post', 'string');

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
                        $redirect == '' ? $authUrl = config::$site.config::$login_redirect : $authUrl = base64_decode($redirect);
                        application::redirect($authUrl);
                        return true;

                    }
                }
            }
        }

    }

    /**
     * Method to login into the application
    */
    public function login()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            application::trigger('onLogin', $_POST);
        }

    }

    /**
     * Method to logout the application
    */
    public function logout()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            //register session
            session::destroySession();

            Application::redirect(url::genUrl('/index.php?view=home'));
            return true;
        }
    }

    /**
     * Method to validate user for the first time into the application after a successful registration
    */
    public function validate()
    {
        if(parent::allowTask(__FUNCTION__, $_GET['task'])) {

            $sitename = config::$sitename;

            //if token...
            if(isset($_GET['token'])) {
                $result = database::updateField('#_users', 'block', 0, 'token', $_GET['token']);
                if($result) {
                    if(config::$admin_mails == 1) {
                        $subject = "Nuevo registro en $sitename";
                        $body    = "Nuevo registro en $sitename, Un nuevo usuario se ha registrado en $sitename.";
                        $this->sendMail(config::$email, config::$email, $subject, $body);
                    }
                    application::setMessage(language::replace('FOXY_REGISTER_WELCOME_MSG_SUCCESS',  $sitename), 'success');
                } else {
                    application::setMessage(language::get('FOXY_REGISTER_WELCOME_MSG_ERROR'), 'danger');
                }
                application::redirect(config::$site);
            }
        }
    
    }
}
