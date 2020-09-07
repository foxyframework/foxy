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

class blocks extends model
{
	private $table  = '#_blocks';
	private $view   = 'blocks';
	private $key    = 'id';
	private $order  = 'ordering';
	private $dir    = 'ASC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_blocks` AS i';
	private $sql    = 'SELECT i.*, p.title AS page FROM `#_blocks` AS i INNER JOIN `#_pages` AS p ON i.pageId = p.id';

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
		
		$this->sql .= ' ORDER BY '.$this->order.' DESC LIMIT '.$offset.', '.$no_of_records_per_page;

		database::query($this->sql);
		
		$this->total_pages = ceil(database::num_rows() / $no_of_records_per_page);
		
		$_SESSION['total_pages'] = ceil($count_rows / $no_of_records_per_page);	
		
		return database::fetchObjectList();
	}

	/**
	 * Method to get and item by id
	 * @return object 
	*/
	public function getItemById()
	{
		$id  = application::getVar('id', 0, 'get');

		return parent::getItem($this->table, $this->key);
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
			$link = config::$site.'/index.php?view='.$this->view.'&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_REMOVE_SUCCESS'), 'success');
		} else {
			$link = config::$site.'/index.php?view='.$this->view.'&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_REMOVE_ERROR'), 'danger');
		}
        application::redirect($link);
	}

	/**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function status()
	{
		$id   	= application::getVar('id', '', 'get');

		$result = database::query('UPDATE'.$this->table.' SET SET `status` = NOT `status` WHERE '.$this->key.' = '.$id);

		if($result) {
			$link = config::$site.'/index.php?view='.$this->view.'&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_STATUS_SUCCESS'), 'success');
		} else {
			$link = config::$site.'/index.php?view='.$this->view.'&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_STATUS_ERROR'), 'danger');
		}
        application::redirect($link);
	}

	/**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function saveBlock()
	{
		$id = application::getVar('id', 0, 'post', 'int');

        if($id == 0) {
          database::query('SELECT MAX(ordering)+1 FROM '.$this->table);
          $_POST['ordering'] = database::loadResult();	
          $result = database::insertRow($this->table, $_POST);
        } else {
          $result = database::updateRow($this->table, $_POST, $this->key, $id);
        }
    
        if($result) {
          application::setMessage(language::get('FOXY_ITEM_SAVE_SUCCESS'), 'success');
        } else {
          application::setMessage(language::get('FOXY_ITEM_ERROR_ERROR'), 'danger');
        }
            
        $link = config::$site.'/index.php?view='.$this->view.'&layout=admin&id='.$id;
    
        application::redirect($link);
	}

	/**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function saveBlockItem()
	{
		$id = application::getVar('id', 0, 'post', 'int');
		unset($_POST['id']);
		foreach ($_POST as $k => $v) {
			$post[$k] = $v;
		}

		$params = json_encode($post);
        
        $result = database::updateField($this->table, 'params', $params, $this->key, $id);
    
        if($result) {
          application::setMessage(language::get('FOXY_ITEM_SAVE_SUCCESS'), 'success');
        } else {
          application::setMessage(language::get('FOXY_ITEM_ERROR_ERROR'), 'danger');
        }
            
        $link = config::$site.'/index.php?view='.$this->view.'&layout=admin&id='.$id;
    
        application::redirect($link);
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
