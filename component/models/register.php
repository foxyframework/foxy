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
            $link       = config::$site.'/index.php?task=register.validate&token='.session::getVar('token');
            $body       = language::replace('FOXY_REGISTER_WELCOME_BODY', session::getVar('username'),  config::$sitename, $link);
            $send       = $this->sendMail(session::getVar('email'), session::getVar('email'), $subject, $body);

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

      $result = database::updateRow("#_users", $obj, 'id', session::getVar('id'));

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
                    if(settings::get('admin_mails', 0) == 1) {
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
