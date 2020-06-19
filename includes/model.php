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

class model
{
    /**
     * @access public
     * @param table string Database table
     * @param key string Database primary key
     * @param id int primary key value
     * @return bool
    */
    public function getItem($table, $key, $id=0)
	{
		$db     = factory::get('database');
		$app    = factory::get('application');
		$config = factory::get('config');

		$id = $app->getVar('id', $id);

		if($id > 0) {
			$sql = "SELECT * FROM $table WHERE $key = $id";
			if($config->debug == 1) { echo 'getItem: '.$sql.'\n'; }
			$db->query($sql);

			return $db->fetchObject();
		}
    }

    /**
     * Mthod to save the view params
     * @access public
     * @return void
    */
    public function saveParams()
	{
        $app  = factory::get('application');
        $lang = factory::get('language');

        $view = $app->getVar('view');
        $json = array();

        foreach($_POST as $k => $v) {
            $json[$k] = $v;
        }

        $fp = fopen(FOXY_COMPONENT.DS.'views'.DS.$view.DS.'params.json', 'w');
        fwrite($fp, json_encode($json));
        fclose($fp);  
        
        $app->setMessage($lang->get('FOXY_SUCCESS_SAVE_PARAMS'), 'success');
        $app->redirect('index.php?view='.$view.'&layout=admin');
    }

    /**
     * Method to get the view params
     * @param string $view The view name
     * @access public
     * @return object
    */
    public function getParams($view)
	{
        $app  = factory::get('application');

        $path = FOXY_COMPONENT.DS.'views'.DS.$view.DS.'params.json';
        if(file_exists($path)) {
            $json = json_decode(file_get_contents($path));
            return $json; 
        }
    }

    /**
     * Method to check if is the task that user needs
     * @param string $task The task name
     * @access public
     * @return bool
    */
    public function allowTask($task, $get)
	{
        if (strpos($task, '.') !== false) { 
            $parts = explode('.', $task);
            $task  = $parts[1];
        }

        if($task != $get) { return false; }

        return true;
    }

    /**
     * Method to get all the database users
     * @access public
     * @return object
    */
	public static function getUsers()
	{
		$db = factory::get('database');
		$db->query('select * from #_users WHERE block = 0');
		return $db->fetchObjectList();
	}

    /**
     * Method to know if session user is an admin
     * @access public
     * @return bool
    */
    function isAdmin() {

        $user = factory::get('user');

        if($user->level == 1) { return true; }

        return false;
    }

    /**
     * Method to secure the wishlist
    */
    public function tokenCheck()
    {
        $db     = factory::get('database');

        //exit if its the token owner...
        $db->query('SELECT token FROM `#_users` WHERE username = '.$_GET['username']);
        $token = $db->loadResult();
        if($token != $_GET['token']) {
            return false;
        }

        return true;
    }

    /**
     * Send email to the user
     * @param $mail string the user email
     * @param $name string the username
     * @param $subject string the mail subject
     * @param $body string the mail body
     * @return boolean true if success false if not
    */
    public static function sendMail($email, $name, $subject, $body)
    {
        $mail   = factory::get('mailer');
        $config = factory::get('config');

        @ob_start();
		include 'assets/mail/mail.html';
		$html = @ob_get_clean();
		$htmlbody = str_replace('{{LOGO}}', $config->site.'/assets/img/mail_logo.png', $html);
		$htmlbody = str_replace('{{BODY}}', $body, $htmlbody);

        $mail->setFrom($config->email, $config->sitename);
        $mail->addRecipient($name, $email);
        $mail->setReplyTo($config->email);
        $mail->Subject($subject);
        $mail->Body($htmlbody);
        if($mail->send()) {
            return true;
        }
        return false;
    }

	/**
     * Method to create a pagination
     * @params array $filters all the GET vars
     * @return string
    */
    public function pagination($filters)
    {
		$app  = factory::get('application');
		$lang = factory::get('language');

    	$total_pages = $_SESSION['total_pages'];
		$html = array();
        $string = '';

        $page = (empty($filters['page'])) ? 1 : $filters['page'];
        unset($filters['page']);

		foreach($filters as $k => $v) {
			$string .= '&'.$k.'='.$v;
		}

        $first = $lang->get('FOXY_FIRST');
        $last  = $lang->get('FOXY_LAST');
        $pages = $lang->get('FOXY_PAGES');

        //no do not go over index
        $before5 = ($page - 5 < 1) ? 1 : $page - 5;

        $max5laps = 0;
        $after5 = $page;
        while ($after5 <= $total_pages && $max5laps < 5) {
            $after5++;
            $max5laps++;
        }
        $after5--;

        $html[] = '<div class="pager my-3">';

        if($total_pages > 0){
            $html[] = '<nav aria-label="">';
            $html[] = '<ul class="pagination">';
            //FIRTS
            $html[] = '<li class="page-item ';
            if($page <= 1 ) $html[] = 'disabled';
            $html[] = '"><a class="page-link" rel="nofollow" href="index.php?'.$string.'&page=1">'.$first.'</a></li>';
            //BEFORE
            $html[] = '<li class="page-item ';
            if($page <= 1 ) $html[] = 'disabled';
            $html[] = '"><a class="page-link" rel="nofollow" href="index.php?'.$string.'&page='. $before5 .'">«</a></li>';

            //While PAGES
            $max5laps = 0;
            $field = $page;
            while ($field <= $total_pages && $max5laps < 5) {
                $html[] = '<li class="page-item ';
                if($page == $field ) $html[] = 'active';
                $html[] = '"><a class="page-link" rel="nofollow" href="index.php?'.$string.'&page='.$field.'">'.$field.'</a></li>';

                $field++;
                $max5laps++;
            }

            //AFTER
            $html[] = '<li class="page-item ';
            if($page == $total_pages || $after5 == $total_pages) $html[] = 'disabled';
            $html[] = '"><a class="page-link" rel="nofollow" href="index.php?'.$string.'&page='. $after5 .'">»</a></li>';
            //LAST
            $html[] = '<li class="page-item ';
            if($page < 1 || $page == $total_pages || $after5 == $total_pages) $html[] = 'disabled';
            $html[] = '"><a class="page-link" rel="nofollow" href="index.php?'.$string.'&page='. $total_pages .'">'.$last.'</a></li>';
            $html[] = '</ul>';
            $html[] = '</nav>';
            //TOTAL PAGES
            $html[] = '<p style="font-size: small">'.$pages.' '.$total_pages.'</p>';

        }

        $html[] = '</div>';

		return implode($html);
    }
}
