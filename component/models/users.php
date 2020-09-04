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

class users extends model
{
  private $table  = '#_users';
	private $view   = 'users';
	private $key    = 'id';
	private $order  = 'ordering';
	private $dir    = 'DESC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_users` AS i';
	private $sql    = 'SELECT * FROM `#_users` AS i';

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
		
		$this->sql .= ' ORDER BY id DESC LIMIT '.$offset.', '.$no_of_records_per_page;

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
		$id  = application::getVar('id', 0, 'post');

		return parent::getItem($this->table, $this->key, $id);
	}
  
  /*
  * Method to save a new users into database
  *
  */
  public function saveItem()
  {
      $obj = new stdClass();
      $obj->username      = application::getVar('username');
      $obj->email         = application::getVar('email');
      $obj->password      = application::encryptPassword(application::getVar('password'));
      $obj->level         = application::getVar('usergroup');
      $obj->block         = 0;
      $obj->token         = user::genToken($obj->email);
      $obj->registerDate  = date('Y-m-d H:i:s');
      $obj->language      = 'ca-es';
      $obj->cargo         = '';
      $obj->bio           = '';
      $obj->address       = '';
      $obj->template      = 'green';
      $obj->apikey        = '';

      $result = database::insertRow($this->table, $obj);

      if($result) {
        application::setMessage(language::get('FOXY_ITEM_SAVE_SUCCESS'), 'success');
      } else {
        application::setMessage(language::get('FOXY_ITEM_SAVE_ERROR'), 'danger');
      }
      application::redirect(config::$site.'/index.php?view=users&layout=admin');
  }

  /**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function removeItem()
	{
		$id   	= application::getVar('id', '', 'get');

		$result = database::query('DELETE FROM '.$this->table.' WHERE '.$this->key.'  = '.$id);

		if($result) {
			$link = config::$site.'/index.php?view=users&layout=admin';
      application::setMessage(language::get('FOXY_ITEM_REMOVE_SUCCESS'), 'success');
		} else {
			$link = config::$site.'/index.php?view=users&layout=admin';
			application::setMessage(language::get('FOXY_ITEM_REMOVE_ERROR'), 'danger');
		}

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
