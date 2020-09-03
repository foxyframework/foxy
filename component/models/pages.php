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

class pages extends model
{
  private $table  = '#_pages';
	private $view   = 'pages';
	private $key    = 'id';
	private $order  = 'id';
	private $dir    = 'ASC';
	private $rows   = 'SELECT COUNT(i.id) FROM `#_pages` AS i';
  private $sql    = 'SELECT * FROM `#_pages` AS i';
  
  public function getList() 
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
      $id  = application::getVar('id', 0, 'post');

      return parent::getItem($this->table, $this->key, $id);
    }
    
    /*
    * Method to save a new users into database
    *
    */
    public function savePage()
    {
        $obj = new stdClass();
        $obj->title         = application::getVar('title', '', 'post');
        $obj->translation   = application::getVar('translation', '', 'post');
        $obj->url           = application::getVar('url', '', 'post');
        $obj->auth          = application::getVar('auth', 0, 'post', 'int');
        $obj->type          = application::getVar('type', 0, 'post', 'int');
        $obj->module        = application::getVar('module', '', 'post');
        $obj->template      = application::getVar('template', '', 'post');
        $obj->inMenu        = application::getVar('inMenu', '', 'post');

        $result = database::insertRow("#_menu", $obj);

        if($result) {
          application::setMessage(language::get('FOXY_MENU_SAVE_SUCCESS'), 'success');
        } else {
          application::setMessage(language::get('FOXY_MENU_SAVE_ERROR'), 'danger');
        }
        application::redirect(config::$site.'/index.php?view=pages&layout=admin');
    }
}
