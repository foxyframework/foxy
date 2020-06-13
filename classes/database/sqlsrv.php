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

class sqlsrv {
	
  	public $last_query;
  	public $result;
  	public $connection_id;
  	public $num_queries = 0;
      
    /**
     * Constructor
    */
    public function __construct() {
        $config = factory::get('config');
		$connectionInfo = array( "Database"=>$config->database, "UID"=>$config->user, "PWD"=>$config->pass, "CharacterSet" => 'UTF-8');
		$this->connection_id = sqlsrv_connect( $config->host, $connectionInfo);	
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
		$this->result = sqlsrv_query( $this->connection_id, $this->last_query);
	    return $this->result;
  	}
    
    /**
     * Returns the first row of a query
    */
    public function loadResult() {
		sqlsrv_fetch($this->result);
		return sqlsrv_get_field($this->result, 0);
        
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
			$val = trim($val);
            $fis[]  = "$field";
			if($val !== 'null') {
				$vars[] = "".$this->quote($val, true)."";
			} else {
				$vars[] = $val;
			}
        }
        $query .= " (".implode(", ", $fis).") VALUES (".implode(", ", $vars).")";

        if ($this->result = $this->query($query))
        return $this->result;
        else die( $this->last_query.' '.print_r( sqlsrv_errors(), true));
    }
    
    /**
     * Method to update table
     * @param $table
     * @param $array
     * @param $idField the key used in where clausule
     * @param $id value of the key
     * @since 1.0
    */
    public function updateRow($table, $array, $idField, $id) {
        $config = factory::get('config');
        $query = "UPDATE ".str_replace('#_', $config->dbprefix, $table)." SET ";
        $vars = array();
        foreach($array as $field=>$val) {
			$val = trim($val);
			if(strtotime($val) !== false) {
				str_replace('-', '', $val);
			}
			if($field != $idField) {
						$val != 'null' ? $value = $this->quote($val, true) : $value = $val;
						$vars[] = "$field"." = ".$value."";
			}
        }
        $query .= implode(", ", $vars)." WHERE $idField = ".$this->quote($id);
	//echo $query;
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
        if(strtotime($field) !== false) {
                str_replace('-', '', $field);
        }
        $query = "UPDATE ".str_replace('#_', $config->dbprefix, $table)." SET ";        
        $value = "$field"." = ".$this->quote(trim($value), false)."";
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
        $query = "DELETE FROM $table WHERE $idField = ".$this->quote($id);
        $this->result = $this->query($query);
        return $this->result;
    }
	
    /**
     * Method to create an object for a single row
     * @return object
     * @since 1.0
    */
	public function fetchObject( )
	{
		return sqlsrv_fetch_object( $this->result );
	}
    
    /**
     * Method to create an array for a single row
     * @return array
     * @since 1.0
    */
	public function fetchArray( )
	{
		return sqlsrv_fetch_array( $this->result );
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
	public function num_rows()
	{
		return sqlsvr_num_rows( $this->result );
	}
	
    
    /**
     * Method to quote and optionally escape a string to database requirements for insertion into the database.
	 * @param   string   $text    The string to quote.
	 * @return  string  The quoted input string.
	 * @since   1.0
	*/
	public function quote($text, $escape=true)
	{
		if($escape == true) { $text = $this->escape($text); }
		return '\'' . $text . '\'';
	}

	/**
     * Method to escape strings
     * @return string
     * @since 1.0
    */
    public function escape( $text )
    {
    	return addslashes( $text );
    }
	
    /**
     * Method to return the number of affected rows in the last query
     * @return int
     * @since 1.0
    */
  	public function affected_rows(  )
  	{
		return sqlsrv_rows_affected($this->result);
  	}
      
    /**
     * Method to return a complete error report
     * @return string
     * @since 1.0
    */
    public function getError()
    {
	    return sqlsrv_errors();
    }
    
    /**
     * Method to frees the memory associated with a result
    */
    public function free()
    {
	    return sqlsrv_free_stmt($this->result);
    }
    
    /**
     * Method to close a connection
     * @since 1.0
    */
    public function close()
    {
	    return sqlsrv_close($this->connection_id);
    }
}

?>
