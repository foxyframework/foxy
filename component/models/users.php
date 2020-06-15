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
	private $order  = 'id';
	private $dir    = 'DESC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_users` AS i';
	private $sql    = 'SELECT * FROM `#_users` AS i';

	public function getList($tag = '')
	{
		$db     = factory::get('database');
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
  
  /*
  * Method to save a new users into database
  *
  */
  public function saveItem()
  {
      $app  = factory::get('application');
      $db   = factory::get('database');
      $user = factory::get('user');
      $lang = factory::get('language');

      $obj = new stdClass();
      $obj->username      = $app->getVar('username');
      $obj->email         = $app->getVar('email');
      $obj->password      = $app->encryptPassword($app->getVar('password'));
      $obj->level         = $app->getVar('usergroup');
      $obj->block         = 0;
      $obj->token         = $user->genToken($obj->email);
      $obj->registerDate  = date('Y-m-d H:i:s');
      $obj->language      = 'ca-es';
      $obj->cargo         = '';
      $obj->bio           = '';
      $obj->address       = '';
      $obj->template      = 'green';
      $obj->apikey        = '';

      $result = $db->insertRow($this->table, $obj);

      if($result) {
        $app->setMessage($lang->get('FOXY_ITEM_SAVE_SUCCESS'), 'success');
      } else {
        $app->setMessage($lang->get('FOXY_ITEM_SAVE_ERROR'), 'danger');
      }
      $app->redirect($config->site.'/index.php?view=users&layout=admin');
  }

  /**
	 * Method to remove and item by id
	 * @return object 
	*/
	public function removeItem()
	{
		$db  	    = factory::get('database');
		$app 	    = factory::get('application');
		$user 	  = factory::get('user');
    $config   = factory::get('config');
    $lang 		= factory::get('language');

		$id   	= $app->getVar('id', '', 'get');

		$result = $db->query('DELETE FROM '.$this->table.' WHERE '.$this->key.'  = '.$id);

		if($result) {
			$link = $config->site.'/index.php?view=users&layout=admin';
      $app->setMessage($lang->get('FOXY_ITEM_REMOVE_SUCCESS'), 'success');
		} else {
			$link = $config->site.'/index.php?view=users&layout=admin';
			$app->setMessage($lang->get('FOXY_ITEM_REMOVE_ERROR'), 'danger');
		}

    $app->redirect($link);
	}
}
