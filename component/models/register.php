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
        parent::allowTask('register.checkEmail', $_GET['task']);

        $db         = factory::get('database');
        $email      = $_GET['email'];

        $db->query('select id from #_users where email = '.$db->quote($email));
        if($id = $db->loadResult()) {
            echo false;
        } else {
            echo true;
        }
    }

    /**
     * Method to register a new user
    */
    public function register()
    {
        parent::allowTask('register.register', $_GET['task']);

        $config = factory::get('config');
        $app    = factory::get('application');
        $db     = factory::get('database');
        $user   = factory::get('user');
        $lang   = factory::get('language');
        $url    = factory::get('url');

        //si un campo esta vacio abortamos...
        if($_POST['email'] == "" || $_POST['password'] == "" || $_POST['password2'] == "") {
            $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
            $app->redirect($url->genUrl('/index.php?view=register'));
            return false;
        }

        //check if email exists...
        $db->query('select id from #_users where email = '.$db->quote($_POST['email']));
        if($id = $db->loadResult()) {
            $app->setMessage($lang->get('El email ya existe, por favor elige otro'), 'danger');
            $app->redirect($url->genUrl('/index.php?view=register'));
            return false;
        }

        if($_POST['password'] === $_POST['password2']) {

            unset($_POST['password2']);
            unset($_POST['auth_token']);

            //create user
            $_POST['username']      = $_POST['email'];
            $_POST['email']         = $_POST['email'];
            $_POST['password']      = $app->encryptPassword($_POST['password']);
            $_POST['registerDate']  = date('Y-m-d H:i:s');
            $token                  = $user->genToken($_POST['email']);
            $_POST['token']         = $token;
            $_POST['level']         = 2;
            $_POST['language']      = 'en-gb';
            $result = $db->insertRow('#_users', $_POST);

            $lastid = $db->lastId();

            if($result && $result2) {
                //send a confirmation to the user...
                $subject    = $lang->replace('FOXY_REGISTER_WELCOME_SUBJECT', $config->sitename);
                $link       = $config->site.'/index.php?task=register.validate&token='.$token;
                $body       = $lang->replace('FOXY_REGISTER_WELCOME_BODY', $_POST['username'],  $config->sitename, $link);
                $send       = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);

                if($send) {
                    $app->setMessage($lang->replace('FOXY_REGISTER_SUCCESS_MSG', $config->sitename), 'success');
                    $app->redirect($url->genUrl('/index.php?view=home'));
                    exit(0);
                } else {
                    //mostrar el link de activacion en el mensaje ya que fallo el email...
                    $app->setMessage($lang->replace('FOXY_REGISTER_EMAIL_ERROR_MSG', $link), 'danger');
                    $app->redirect($url->genUrl('/index.php?view=register'));
                    return false;
                }
            } else {
                $app->setMessage($lang->get('FOXY_REGISTER_ERROR_MSG'), 'danger');
                $app->redirect($url->genUrl('/index.php?view=register'));
                return false;
            }
        } else {
            $app->setMessage($lang->get('FOXY_REGISTER_PASSWORDS_NOT_MATCH_MSG'), 'danger');
            $app->redirect($url->genUrl('/index.php?view=register'));
            return false;
        }
    }

    /**
     * Method to reset the user password
    */
    public function resendActivation()
    {
        parent::allowTask('register.resendActivation', $_GET['task']);

        $config = factory::get('config');
        $app    = factory::get('application');
        $user   = factory::get('user');
        $lang   = factory::get('language');
        $url    = factory::get('url');

        //send a confirmation to the user...
        $subject    = $lang->replace('FOXY_REGISTER_WELCOME_SUBJECT', $config->sitename);
        $link       = $config->site.'/index.php?task=register.validate&token='.$user->token;
        $body       = $lang->replace('FOXY_REGISTER_WELCOME_BODY', $user->username,  $config->sitename, $link);
        $send       = $this->sendMail($user->email, $user->email, $subject, $body);

        if($send) {
            $app->setMessage($lang->get('FOXY_REGISTER_RESET_SUCCESS_MSG'), 'success');
            $app->redirect($url->genUrl('/index.php?view=home'));
        } else {
            $app->setMessage($lang->get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
        }
    }

    /**
     * Method to reset the user password
    */
    public function reset()
    {
        parent::allowTask('register.reset', $_GET['task']);

        $config = factory::get('config');
        $app    = factory::get('application');
        $db     = factory::get('database');
        $user   = factory::get('user');
        $lang   = factory::get('language');
        $url        = factory::get('url');

        //si un campo esta vacio abortamos...
        if($_POST['email'] == "") {
            $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
            $app->redirect($url->genUrl('/index.php?view=register&layout=reset'));
            return false;
        }

        $email  = $db->quote($_POST['email']);

        $db->query("SELECT id FROM #_users WHERE email = $email AND block = 0");
        $id = $db->loadResult();
        $newpassword = uniqid();
        $password = $app->encryptPassword($newpassword);
        $result = $db->updateField('#_users', 'password', $password, 'id', $id);
        //send email to user...
        if($result) {
            //send a confirmation to the user...
            $subject  = $lang->replace('FOXY_REGISTER_RESET_SUBJECT', $config->sitename);
            $body     = $lang->replace('FOXY_REGISTER_RESET_BODY', $_POST['email'], $config->sitename, $newpassword);
            $send     = $this->sendMail($_POST['email'], $_POST['email'], $subject, $body);

            if($send) {
                $app->setMessage($lang->get('FOXY_REGISTER_RESET_SUCCESS_MSG'), 'success');
                $app->redirect($url->genUrl('/index.php?view=home'));
            } else {
                $app->setMessage($lang->get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
            }
        } else {
            $app->setMessage($lang->get('FOXY_REGISTER_RESET_ERROR_MSG'), 'danger');
        }
    }

    /**
     * Method to login into the application
    */
    public function twosteps()
    {
        parent::allowTask('register.twosteps', $_GET['task']);

        $config     = factory::get('config');
        $app        = factory::get('application');
        $db         = factory::get('database');
        $user       = factory::get('user');
        $lang       = factory::get('language');
        $session    = factory::get('session');
        $url        = factory::get('url');

        $g = new \Google\Authenticator\GoogleAuthenticator();

        if($app->getVar('otp') == '') {
            $email      = $app->getVar('email', '', 'post', 'string');
            $password   = $app->getVar('password', '', 'post', 'string');
            $redirect   = $app->getVar('return', '', 'post', 'string');
            $token      = $app->getVar('token', '', 'post', 'string');

            //si un campo esta vacio abortamos...
            if($email == "" || $password == "") {
                $app->setMessage($lang->get('FOXY_ALL_FIELDS_ARE_MANDATORY'), 'danger');
                $app->redirect($config->site.$config->login_redirect);
                return false;
            }

            $db->query("SELECT password FROM `#_users` WHERE email = ".$db->quote($email)." AND block = 0");
            $dbpass = $db->loadResult();
            if($app->decryptPassword($password, $dbpass)) {
                //get the user id and store it before authenticate
                $db->query("SELECT id FROM `#_users` WHERE email = ".$db->quote($email)." AND block = 0");
                if($id = $db->loadResult()) {
                    $session->setVar('tmpid', $id);
                }
            } else {
                $app->setMessage($lang->get('PFOXY_LOGIN_PASSWORD_NOT_MATCH_ERROR_MSG'), 'danger');
                $app->redirect($url->genUrl('/index.php?view=register&layout=login'));
                return false;
            }
        }

        if ($session->getVar('tmpid') != '' && !$user->getAuth() && $app->getVar('otp') == '') {

            //generate new token and save into database...
            $secret = $user->genToken(random_bytes(10));
            $session->setVar('secret', $secret);
            $db->updateField('#_users', 'token', $secret, 'id', $session->getVar('tmpid'));
            $app->redirect($url->genUrl('/index.php?view=register&layout=authcode'));

        } else {
            if($app->getVar('otp') != '') {
                $tmpId = $app->getVar('tmpId'); 
                if ($g->checkCode('Foxy'.$user->getToken($tmpId), $app->getVar('otp'))) {   
                    
                    $user->setAuth($tmpId);

                    //register session
                    $session->createSession();

                    $db->updateField('#_users', 'lastvisitDate',  $app->getVar('lastvisitDate'), 'id', $tmpId);
                    $app->setMessage($lang->replace('FOXY_LOGIN_SUCCESS_MSG',  $user->username), 'success');
                    $redirect == '' ? $authUrl = $config->site.$config->login_redirect : $authUrl = base64_decode($redirect);
                    $app->redirect($authUrl);
                    return true;

                }
            }
        }

    }

    /**
     * Method to login into the application
    */
    public function login()
    {
        parent::allowTask('register.login', $_GET['task']);

        $config     = factory::get('config');
        $app        = factory::get('application');
        $db         = factory::get('database');
        $user       = factory::get('user');
        $lang       = factory::get('language');
        $session    = factory::get('session');
        $url        = factory::get('url');

        $email    = $app->getVar('email', '', 'post', 'string');
        $password = $app->getVar('password', '', 'post', 'string');
        $redirect = $app->getVar('return', '', 'post', 'string');
        $token    = $app->getVar('token', '', 'post', 'string');

        //si un campo esta vacio abortamos...
        if($email == "" || $password == "") {
            $app->setMessage($lang->get('Rellena todos los campos por favor'), 'danger');
            $app->redirect($config->site.$config->login_redirect);
            return false;
        }

		$db->query("SELECT password FROM `#_users` WHERE email = ".$db->quote($email)." AND block = 0");
		$dbpass = $db->loadResult();
        if($app->decryptPassword($password, $dbpass)) {

	        $db->query("SELECT id FROM `#_users` WHERE email = ".$db->quote($email)." AND block = 0");
	        if($id = $db->loadResult()) {
                $user->setAuth($id);

                //register session
                $session->createSession();

                $db->updateField('#_users', 'lastvisitDate',  $app->getVar('lastvisitDate'), 'id', $id);
                $app->setMessage($lang->replace('FOXY_LOGIN_SUCCESS_MSG',  $user->username), 'success');
                $redirect == '' ? $authUrl = $config->site.$config->login_redirect : $authUrl = base64_decode($redirect);
                $app->redirect($authUrl);
                return true;

            } else {
		        $app->setMessage($lang->get('FOXY_LOGIN_ERROR_MSG'), 'danger');
                $app->redirect($url->genUrl('/index.php?view=register&layout=login'));
                return false;
		    }
        } else {
            $app->setMessage($lang->get('Password not match'), 'danger');
            $app->redirect($url->genUrl('/index.php?view=register&layout=login'));
            return false;
        }

    }

    /**
     * Method to logout the application
    */
    public function logout()
    {
        parent::allowTask('register.logout', $_GET['task']);

        $config     = factory::get('config');
        $app        = factory::get('application');
        $session    = factory::get('session');
        $url        = factory::get('url');

        //register session
        $session->destroySession();

        //Unset token and user data from session
        unset($_SESSION['FOXY_userid'], $_SESSION['token'], $_SESSION['userData']);

        $app->redirect($url->genUrl('/index.php?view=home'));
        return true;
      
    }

    /**
     * Method to validate user for the first time into the application after a successful registration
    */
    public function validate()
    {
        parent::allowTask('register.validate', $_GET['task']);

        $config = factory::get('config');
        $app    = factory::get('application');
        $db     = factory::get('database');
        $user   = factory::get('user');
        $lang   = factory::get('language');

        $sitename = $config->sitename;

        //if token...
        if(isset($_GET['token'])) {
            $result = $db->updateField('#_users', 'block', 0, 'token', $_GET['token']);
            if($result) {
                if($config->admin_mails == 1) {
                    $subject = "Nuevo registro en $sitename";
                    $body    = "Nuevo registro en $sitename, Un nuevo usuario se ha registrado en $sitename.";
                    $this->sendMail($config->email, $config->email, $subject, $body);
                }
                $app->setMessage($lang->replace('FOXY_REGISTER_WELCOME_MSG_SUCCESS',  $sitename), 'success');
            } else {
                $app->setMessage($lang->get('FOXY_REGISTER_WELCOME_MSG_ERROR'), 'danger');
            }
            $app->redirect($config->site);
        }
    
    }
}
