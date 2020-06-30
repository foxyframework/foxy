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

        $lang    = factory::get('language');
        //$url     = factory::get('url');
        $app     = factory::get('application');
        $config  = factory::get('config');

        $token   = $_POST['g-recaptcha-response'];

        if($config->recaptcha == 1) {
            $ip 		    = $_SERVER['REMOTE_ADDR'];
		    $response	    = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$config->secret_key."&response=".$token."&remoteip=".$ip);
		    $responseKeys   = json_decode($response, true);
        }

        $name    = $app->getVar('name');
        $phone   = $app->getVar('phone');
        $email   = $app->getVar('email');
        $message = $app->getVar('message');

        $subject = $lang->replace('FOXY_CONTACT_SUBJECT', $config->sitename);
        $body    = $lang->replace('FOXY_CONTACT_BODY', $name, $phone, $email, $message);

        if($config->recaptcha == 0 || $responseKeys['score'] >= 0.5) {

            $send = $this->sendMail($config->email, $config->sitename, $subject, $body);
        }

        if($send) {
            $app->redirect(url::genUrl('index.php?view=contact'), $lang->get('FOXY_CONTACT_SEND_SUCCESS'), 'success');
        } else {
            $app->redirect(url::genUrl('index.php?view=contact'), $lang->get('FOXY_CONTACT_SEND_ERROR'), 'danger');
        }
    }

}
