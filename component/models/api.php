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

class Api
{
    /**
     * Apikey used to retrieve data
     *
     * @var  string
     * @access   private
    */
    private $apikey = '';

    /**
     * Id
     *
     * @var  int
     * @access   private
    */
    private $id = 0;

    /**
     * Table
     *
     * @var  string
     * @access   private
    */
    private $table = '';


    /**
     * retrieve all
     * @access   public
     */
    public function getItems()
    {
        header('Content-Type: application/json');

        $this->apikey = application::getVar('apikey', '');

        try {
            if($this->checkApikey($this->apikey)) {
                $db->query('SELECT * FROM '.$this->table);
                echo json_encode(database::fetchObjectList());
            } else {
                echo json_encode(array(
                    'error' => array(
                        'msg' => 'Not valid apikey',
                        'code' => '500',
                    )
                )); 
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $e->getMessage(),
                    'code' => $e->getCode(),
                )
            ));
        }
        exit();
    }

    /**
     * retrieve all data from a specific id
     * @access   public
     */
    public function getItemById()
    {

        header('Content-Type: application/json');

        $this->apikey  = application::getVar('apikey', '');
        $this->id      = application::getVar('id', '');

        try {
            if($this->checkApikey($apikey)) {
                database::query('SELECT * FROM '.$this->table.' WHERE id = '.$this->id);
                echo json_encode(database::fetchObject());
            } else {
                echo json_encode(array(
                    'error' => array(
                        'msg' => 'Not valid apikey',
                        'code' => '500',
                    )
                )); 
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $e->getMessage(),
                    'code' => $e->getCode(),
                )
            ));
        }
        exit();
    }

    /**
     * check if apikey is valid
     * @param    string  $apikey      User apikey
     * @access   public
     */
    private function checkApikey($apikey)
    {
        database::query('SELECT id FROM `#_users` WHERE apikey = '.database::quote($apikey).' AND level = 1 AND block = 0');
        if($id = database::loadResult()) {
            return true;
        }
        return false;
    }

}
?>
