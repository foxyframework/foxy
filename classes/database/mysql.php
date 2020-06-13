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

class mysql {
	
  	public $last_query;
  	public $result;
  	public $connection_id;
  	public $num_queries = 0;
      
    /**
     * Constructor
    */
    public function __construct() {
    
        $config = factory::get('config');

        $this->connection_id = new mysqli ( $config->host, $config->user, $config->pass, $config->database );
    	
		if ($this->connection_id->connect_errno) {
			die('Connect Error (' . $this->connection_id->connect_errno . ') '. mysqli_connect_error);
		}

		if (!$this->connection_id->set_charset("utf8")) {
			die('Connect Error (' . $this->connection_id->connect_errno . ') '. mysqli_connect_error);
		}
		
		return $this->connection_id;
    }
	
    /**
     * Method to query a table
     * @param $query
     * @since 1.0
    */
    public function query( $query ) {
    
        $config = factory::get('config');
	    $this->last_query = str_replace('#_', $config->dbprefix, $query);
	    $this->num_queries++;
	    $this->result = mysqli_query( $this->connection_id, $this->last_query ) or die( $this->getError() );
	    return $this->result;
    }
    
    /**
     * Returns the first row of a query
    */
    public function loadResult() {
        
        $row = mysqli_fetch_row($this->result);
		return $row[0];        
    }
    
    /**
     * Method to insert array into table
     * @param $table
     * @param $array
     * @since 1.0
    */
    public function insertRow($table, $array) {
    
        $config = factory::get('config');
        $query = "INSERT INTO ".str_replace('#_', $config->dbprefix, $table);
        $fis = array(); 
        $vars = array();
        foreach($array as $field=>$val) {
            $fis[]  = "`$field`";
            $vars[] = "".$this->quote($val, false)."";
        }
        $query .= " (".implode(", ", $fis).") VALUES (".implode(", ", $vars).")";
        if ($this->result = $this->query($query))
        return $this->result;
        else return false;
    }
    
    /**
     * Method to update table
     * @param $table
     * @param $array
     * @param $idField
     * @param $id
     * @since 1.0
    */
    public function updateRow($table, $array, $idField, $id) {
    
        $config = factory::get('config');
        $query = "UPDATE ".str_replace('#_', $config->dbprefix, $table)." SET ";
        $vars = array();
        foreach($array as $field=>$val) {
            $vars[] = "$field"." = ".$this->quote($val, false)."";
        }
        $query .= implode(", ", $vars)." WHERE $idField = ".(int)$id;
        if ($this->result = $this->query($query))
        return $this->result;
        else return false;
    }
    
    /**
     * Method to update a table single field
     * @param $table
     * @param $field
     * @param $value
     * @param $idField
     * @param $id
     * @since 1.0
    */
    public function updateField($table, $field, $value, $idField, $id) {
    
        $config = factory::get('config');
        $query = "UPDATE ".str_replace('#_', $config->dbprefix, $table)." SET ";        
        $value = "`$field`"." = ".$this->quote($value, false)."";
        $query .= $value." WHERE $idField = ".$this->quote($id);
        if ($this->result = $this->query($query))
        return $this->result;
        else return false;
    }
    
    /**
     * Method to delete a table row
     * @param $table string database table
     * @param $idField string field to delete
     * @param $id id int item id
     * @since 1.0
    */
    public function deleteRow($table, $idField, $id) {
    
        $config = factory::get('config');
        $table = str_replace('#_', $config->dbprefix, $table);
        $query = "DELETE FROM $table WHERE $idField = ".(int)$id;
        $this->result = $this->query($query);
        return $this->result;
    }
    
    /**
     * Method to return the last insert id
     * @return int
     * @since 1.0
    */
    public function lastId() {
        return mysqli_insert_id($this->connection_id);
    }
	
    /**
     * Method to create an object for a single row
     * @return object
     * @since 1.0
    */
	public function fetchObject( )
	{
	    return mysqli_fetch_object( $this->result );
	}
    
    /**
     * Method to create an array for a single row
     * @return array
     * @since 1.0
    */
	public function fetchArray( )
	{
	    return mysqli_fetch_array( $this->result );
	}
	
	/**
     * Method to create an object for multiple rows
     * @return object
     * @since 1.0
    */
	public function fetchObjectList()
	{
	    $object = array();

	    while ($row = $this->fetchObject( $this->result )) {
	        $object[] = $row;
	    }
	    
	    $this->free();
	    return $object;
	}

    /**
     * Method to return the number of affected rows
     * @return object
     * @since 1.0
    */
	public function num_rows(  )
	{
	    return mysqli_num_rows( $this->result );
	}
    
    /**
     * Method to quote and optionally escape a string to database requirements for insertion into the database.
	 * @param   string   $text    The string to quote.
     * @param   boolean  $escape true if escape variable
	 * @return  string  The quoted input string.
	 * @since   1.0
	*/
	public function quote($text)
	{
		if(is_numeric($text)) { return $text; } 
		return '\''.str_replace("'", "''", $text).'\'';
	}
	
    /**
     * Method to return the number of affected rows in the last query
     * @return int
     * @since 1.0
    */
  	public function affected_rows(  )
  	{
    	return mysqli_affected_rows( );
  	}
      
    /**
     * Method to return a complete error report
     * @return string
     * @since 1.0
    */
    public function getError()
    {
        return mysqli_errno($this->connection_id)." : ".mysqli_error($this->connection_id);
    }
    
    /**
     * Method to frees the memory associated with a result
    */
    public function free()
    {
        return mysqli_free_result($this->result);
    }
    
    /**
     * Method to close a connection
     * @since 1.0
    */
    public function close()
    {
        return mysqli_close($this->connection_id);
    }
}

?>
