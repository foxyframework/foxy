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

include('includes/model.php');

class contact extends model
{
	public function sendForm() {

        $token   = $_POST['g-recaptcha-response'];

        if(config::$recaptcha == 1) {
            $ip 		    = $_SERVER['REMOTE_ADDR'];
		    $response	    = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".config::$secretKey."&response=".$token."&remoteip=".$ip);
		    $responseKeys   = json_decode($response, true);
        }

        $name    = application::getVar('name');
        $phone   = application::getVar('phone');
        $email   = application::getVar('email');
        $message = application::getVar('message');

        $subject = language::replace('FOXY_CONTACT_SUBJECT', config::$sitename);
        $body    = language::replace('FOXY_CONTACT_BODY', $name, $phone, $email, $message);

        if(config::$recaptcha == 0 || $responseKeys['score'] >= 0.5) {

            $send = $this->sendMail(config::$email, config::$sitename, $subject, $body);
        }

        if($send) {
            application::redirect(url::genUrl('index.php?view=contact'), language::get('FOXY_CONTACT_SEND_SUCCESS'), 'success');
        } else {
            application::redirect(url::genUrl('index.php?view=contact'), language::get('FOXY_CONTACT_SEND_ERROR'), 'danger');
        }
    }

}
