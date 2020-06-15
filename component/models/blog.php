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

class blog extends model
{
	private $table  = '#_articles';
	private $view   = 'blog';
	private $key    = 'id';
	private $order  = 'id';
	private $dir    = 'DESC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_articles` AS i WHERE status = 1';
	private $sql    = 'SELECT * FROM `#_articles` AS i WHERE status = 1';

	public function getList($tag = '')
	{
		$db  = factory::get('database');
		$config = factory::get('config');
		
		if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        
        $no_of_records_per_page = $config->pagination;
        
        $offset = ($page-1) * $no_of_records_per_page;
        
        $db->query($this->rows);
        $count_rows = $db->loadResult();
		
		if($tag != '') {
			$this->sql .= ' AND (FIND_IN_SET('.$db->quote($tag).', tags) > 0)';
		}
		
		$this->sql .= ' ORDER BY id DESC LIMIT '.$offset.', '.$no_of_records_per_page;

		$db->query($this->sql);
		
		$this->total_pages = ceil($db->num_rows() / $no_of_records_per_page);
		
		$_SESSION['total_pages'] = ceil($count_rows / $no_of_records_per_page);	
		
		return $db->fetchObjectList();
	}
	
	/**
	 * trims text to a space then adds ellipses if desired
	 * @param string $input text to trim
	 * @param int $length in characters to trim to
	 * @param bool $ellipses if ellipses (...) are to be added
	 * @param bool $strip_html if html tags are to be stripped
	 * @return string 
	 */
	 public function trimText($input, $length, $ellipses = true, $strip_html = true) 
	 {
		//strip tags, if desired
		if ($strip_html) {
		    $input = strip_tags($input);
		}
	  
		//no need to trim, already shorter than trim length
		if (strlen($input) <= $length) {
		    return $input;
		}
	  
		//find last space within length
		$last_space = strrpos(substr($input, 0, $length), ' ');
		$trimmed_text = substr($input, 0, $last_space);
	  
		//add ellipses (...)
		if ($ellipses) {
		    $trimmed_text .= '...';
		}
	  
		return $trimmed_text;
	}

	public function getItem()
	{
		$db  = factory::get('database');
		$app = factory::get('application');

		$id   = $app->getVar('id', 0, 'get');

		$db->query('UPDATE `#_articles` SET hits = hits + 1 WHERE id = '.$id);
		
		return parent::getItem($this->table, $this->key);
	}

	public function delete()
	{
		$db  = factory::get('database');
		$app = factory::get('application');
		$user = factory::get('user');
		$config = factory::get('config');

		$id   = $app->getVar('id', '', 'get');

		//comprobar si es propietario
		$db->query('SELECT userid FROM #_articles WHERE id = '.$id);
		$userid = $db->loadResult();

		if($user->id == $userid) {

			$result = $db->query('DELETE FROM #_articles WHERE id = '.$id);

			if($result) {
				$link = $config->site.'/index.php?view=blog&layout=panel';
				$type = 'success';
				$msg  = 'El article ha estat esborrat amb exit.';
			} else {
				$link = $config->site.'/index.php?view=blog&layout=edit&id='.$id;
				$type = 'danger';
				$msg  = 'Hi ha hagut un error al intentar esborrar aquest article.';
			}

		} else {
			$link = $config->site.'/index.php?view=blog&layout=panel';
			$type = 'danger';
			$msg  = "No ets propietari d'aquest article.";
		}

		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

	public function saveArticle()
	{
		$db  = factory::get('database');
		$app = factory::get('application');
		$user = factory::get('user');
		$config = factory::get('config');
		$lang = factory::get('language');

		$id     = $app->getVar('id', 0, 'post', 'int');
		$_POST['userid'] = $user->id;

		//comprobar si es propietario
		$db->query('SELECT userid FROM #_articles WHERE id = '.$id);
		$userid = $db->loadResult();

		if($user->id == $userid) {

			if($id == 0) {
				$_POST['hits'] = 0;
				$_POST['status'] = 0;
				$result = $db->insertRow('#_articles', $_POST);

				$subject    = 'Nou artícle a '.$config->sitename;
		        $body       = "L'usuari ".$user->username." ha creat un nou artícle anomenat ".$_POST['titol'];
		        $this->sendMail($config->email, 'Admin', $subject, $body);

			} else {
		    	$_POST['status'] = 1;
		    	$result = $db->updateRow('#_articles', $_POST, 'id', $id);
			}

			if($result) {
				$link = $config->site.'/index.php?view=blog&layout=admin';
				$type = 'success';
				$msg  = "L'article ha estat guardat amb exit.";
			} else {
				$link = $config->site.'/index.php?view=blog&layout=admin&id='.$id;
				$type = 'danger';
				$msg  = 'Hi ha hagut un error al intentar guardar aquest article.';
			}

		} else {
			$link = $config->site.'/index.php?view=blog&layout=panel';
			$type = 'danger';
			$msg  = "No ets propietari d'aquest article.";
		}

		$app->setMessage($msg, $type);
        $app->redirect($link);
	}

	public function renderTags($tags)
	{
		$tags = explode(',', $tags);

		$result = '';

		$i = 0;
		foreach($tags as $tag) {
			if($i != 0) { $result .= ' | '; }
			$result .= '<a href="index.php?view=blog&tag='.$tag.'">'.$tag.'</a>';
			$i++;
		}

		return $result;
	}

	public function clean($string)
	{
   		$string = str_replace('<script>', '', $string);
		$string = str_replace('</script>', '', $string);
   		return $string; // Removes special chars.
	}

	public function getFeed()
	{
		$db  = factory::get('database');
		$config = factory::get('config');

	    $db->query('SELECT * FROM #_articles WHERE status = 1 ORDER BY id DESC');

		$rows = $db->fetchObjectList();

		header("Content-Type: application/rss+xml; charset=utf-8");

		$rssfeed  = '<?xml version="1.0" encoding="UTF-8"?>';
		$rssfeed .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">';
		$rssfeed .= '<channel>';
		$rssfeed .= '<title>'.$config->sitename.'</title>';
		$rssfeed .= '<link>'.$config->site.'</link>';
		$rssfeed .= '<description>'.$config->description.'</description>';
		$rssfeed .= '<language>ca-es</language>';
		$rssfeed .= '<copyright>Copyleft 2018 '.$config->sitename.'</copyright>';
		$rssfeed .= '<image>';
		$rssfeed .= '<title>Surt del Cercle</title>';
		$rssfeed .= '<url>http://surtdelcercle.cat/assets/img/icons/icon32.png</url>';
		$rssfeed .= '<link>http://surtdelcercle.cat/assets/img/icons/icon32.png</link>';
		$rssfeed .= '<width>128</width>';
		$rssfeed .= '<height>128</height>';
		$rssfeed .= '</image>';

		foreach($rows as $row) {

			$rssfeed .= '<item>';
			$rssfeed .= '<title><![CDATA['.$row->titol.']]></title>';
			$rssfeed .= '<description><![CDATA['.$row->texte.']]></description>';
			$rssfeed .= '<link>'.$config->site.'/index.php?view=blog&amp;id='.$row->id.'</link>';
			$rssfeed .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($row->publishDate)).'</pubDate>';
			$rssfeed .= '</item>';
		}

		$rssfeed .= '</channel>';
		$rssfeed .= '</rss>';

		return $rssfeed;
	}

}
