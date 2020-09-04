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
	private $order  = 'ordering';
	private $dir    = 'DESC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_articles` AS i WHERE status = 1';
	private $sql    = 'SELECT * FROM `#_articles` AS i WHERE status = 1';

	public function getList($tag = '')
	{		
		if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        
        $no_of_records_per_page = config::$pagination;
        
        $offset = ($page-1) * $no_of_records_per_page;
        
        database::query($this->rows);
        $count_rows = database::loadResult();
		
		if($tag != '') {
			$this->sql .= ' AND (FIND_IN_SET('.database::quote($tag).', tags) > 0)';
		}
		
		$this->sql .= ' ORDER BY '.$this->order.' DESC LIMIT '.$offset.', '.$no_of_records_per_page;

		database::query($this->sql);
		
		$this->total_pages = ceil(database::num_rows() / $no_of_records_per_page);
		
		$_SESSION['total_pages'] = ceil($count_rows / $no_of_records_per_page);	
		
		return database::fetchObjectList();
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

	/**
	 * Method to get and item by id
	 * @return object 
	*/
	public function getItemById()
	{
		$id  = application::getVar('id', 0, 'post');

		database::query('UPDATE '.$this->table.' SET hits = hits + 1 WHERE '.$this->key.' = '.$id);

		return parent::getItem($this->table, $this->key, $id);
	}

	/**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function removeItem()
	{
		$id   	= application::getVar('id', '', 'get');

		$result = database::query('DELETE FROM '.$this->table.' WHERE '.$this->key.' = '.$id);

		if($result) {
			$link = config::$site.'/index.php?view=blog&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_REMOVE_SUCCESS'), 'success');
		} else {
			$link = config::$site.'/index.php?view=blog&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_REMOVE_ERROR'), 'danger');
		}
        application::redirect($link);
	}

	/**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function saveItem()
	{
		$id     = application::getVar('id', 0, 'post', 'int');

		$_POST['category'] 		= 0;
		$_POST['userid']   		= user::$id;
		$_POST['author_link'] 	= '#';
		$_POST['language'] 		= 'ca-es';
		$_POST['status'] 		= 1;
		if($_POST['alias'] == '') { $_POST['alias'] = $this->clean($_POST['title']); }

		if($id == 0) {
			$_POST['hits']  = 0;
			$result = database::insertRow($this->table, $_POST);

			$subject    = language::replace('FOXY_BLOG_NEW_ARTICLE_SUBJECT', config::$sitename);
			$body       = language::replace('FOXY_BLOG_NEW_ARTICLE_BODY', $user->username, $_POST['title']);
			$this->sendMail(config::$email, 'Admin', $subject, $body);

		} else {
			$result = database::updateRow($this->table, $_POST, $this->key, $id);
		}

		if($result) {
			$link = config::$site.'/index.php?view=blog&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_SAVE_SUCCESS'), 'success');
		} else {
			$link = config::$site.'/index.php?view=blog&layout=admin&id='.$id;
			application::setMessage(language::get('FOXY_ITEM_ERROR_ERROR'), 'danger');
		}

        application::redirect($link);
	}

	/**
	 * Method to render all tags separated by comma
	 * @return string 
	*/
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

	/**
	 * Method to clean a string to be a friendly url
	 * @return string 
	*/
	public function clean($string) {

		$string = trim($string); 
		$string = strtolower($string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		$string = preg_replace("/[\s-]+/", " ", $string);
		$string = preg_replace("/[\s_]/", "-", $string);
	
		return $string;	
	}

	/**
     * Method to elapse a date as a string
	 * @param date $datetime the complete data of the article
	 * @return string
    */
    public function timeElapsed($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
		    'y' => 'any',
		    'm' => 'mes',
		    'w' => 'setmana',
		    'd' => 'dia',
		    'h' => 'hora',
		    'i' => 'minut',
		    's' => 'segon',
		);
		foreach ($string as $k => &$v) {
		    if ($diff->$k) {
		        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		    } else {
		        unset($string[$k]);
		    }
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? 'fa '.implode(', ', $string) : 'just ara';
	}

	/**
	 * Method to generate the rss feed
	 * @return string 
	*/
	public function getFeed()
	{
	    database::query('SELECT * FROM #_articles WHERE status = 1 ORDER BY id DESC');

		$rows = database::fetchObjectList();

		header("Content-Type: application/rss+xml; charset=utf-8");

		$rssfeed  = '<?xml version="1.0" encoding="UTF-8"?>';
		$rssfeed .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">';
		$rssfeed .= '<channel>';
		$rssfeed .= '<title>'.config::$sitename.'</title>';
		$rssfeed .= '<link>'.config::$site.'</link>';
		$rssfeed .= '<description>'.config::$description.'</description>';
		$rssfeed .= '<language>ca-es</language>';
		$rssfeed .= '<copyright>Copyleft 2018 '.config::$sitename.'</copyright>';
		$rssfeed .= '<image>';
		$rssfeed .= '<title>Surt del Cercle</title>';
		$rssfeed .= '<url>'.config::$site.'/assets/img/icons/icon32.png</url>';
		$rssfeed .= '<link>'.config::$site.'/assets/img/icons/icon32.png</link>';
		$rssfeed .= '<width>128</width>';
		$rssfeed .= '<height>128</height>';
		$rssfeed .= '</image>';

		foreach($rows as $row) {

			$rssfeed .= '<item>';
			$rssfeed .= '<title><![CDATA['.$row->title.']]></title>';
			$rssfeed .= '<description><![CDATA['.$row->fulltext.']]></description>';
			$rssfeed .= '<link>'.config::$site.'/index.php?view=blog&amp;id='.$row->id.'</link>';
			$rssfeed .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($row->publishDate)).'</pubDate>';
			$rssfeed .= '</item>';
		}

		$rssfeed .= '</channel>';
		$rssfeed .= '</rss>';

		return $rssfeed;
	}

	/**
     * Method to grab order in tables
     * @access public
     * @return void
    */
    public function reorder()
	{
        $view  = application::getVar('view', '', 'get');
        $id    = json_decode(application::getVar('id', '', 'get'), true);
        $order = json_decode(application::getVar('order', '', 'get'), true);
        
		database::query("UPDATE `".$this->table."` SET ordering = ".$order[1]." WHERE ".$this->key." = ".$id[0]);
		database::query("UPDATE `".$this->table."` SET ordering = ".$order[0]." WHERE ".$this->key." = ".$id[1]);
    }

}
