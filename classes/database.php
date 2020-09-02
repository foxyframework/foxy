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

class Database {
	
    public static $last_query;
    public static $result;
    public static $connection_id;
    public static $num_queries = 0;
      
    /**
     * Constructor
    */
    public static function connect() {

        self::$connection_id = new mysqli ( config::$host, config::$user, config::$pass, config::$database );
    	
		if (self::$connection_id->connect_errno) {
			die('Connect Error (' . self::$connection_id->connect_errno . ') '. mysqli_connect_error);
		}

		if (!self::$connection_id->set_charset("utf8")) {
			die('Connect Error (' . self::$connection_id->connect_errno . ') '. mysqli_connect_error);
		}
		
		return self::$connection_id;
    }
	
    /**
     * Method to query a table
     * @param $query
     * @since 1.0
    */
    public static function query( $query ) {
        self::connect();
	    self::$last_query = str_replace('#_', config::$dbprefix, $query);
	    self::$num_queries++;
	    self::$result = mysqli_query( self::$connection_id, self::$last_query ) or die( self::getError() );
	    return self::$result;
    }
    
    /**
     * Returns the first row of a query
    */
    public static function loadResult() {
        
        $row = mysqli_fetch_row(self::$result);
		return $row[0];        
    }
    
    /**
     * Method to insert array into table
     * @param $table
     * @param $array
     * @since 1.0
    */
    public static function insertRow($table, $array) {
        self::connect();
        $query = "INSERT INTO ".str_replace('#_', config::$dbprefix, $table);
        $fis = array(); 
        $vars = array();
        foreach($array as $field=>$val) {
            $fis[]  = "`$field`";
            $vars[] = "".self::quote($val, false)."";
        }
        $query .= " (".implode(", ", $fis).") VALUES (".implode(", ", $vars).")";
        if (self::$result = self::query($query))
        return self::$result;
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
    public static function updateRow($table, $array, $idField, $id) {
        self::connect();
        $query = "UPDATE ".str_replace('#_', config::$dbprefix, $table)." SET ";
        $vars = array();
        foreach($array as $field=>$val) {
            $vars[] = "$field"." = ".self::quote($val, false)."";
        }
        $query .= implode(", ", $vars)." WHERE $idField = ".(int)$id;
        if (self::$result = self::query($query))
        return self::$result;
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
        self::connect();
        $query = "UPDATE ".str_replace('#_', config::$dbprefix, $table)." SET ";        
        $value = "`$field`"." = ".self::quote($value, false)."";
        $query .= $value." WHERE $idField = ".self::quote($id);
        if (self::$result = self::query($query))
        return self::$result;
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
        self::connect();
        $table = str_replace('#_', config::$dbprefix, $table);
        $query = "DELETE FROM $table WHERE $idField = ".(int)$id;
        self::$result = self::query($query);
        return self::$result;
    }
    
    /**
     * Method to return the last insert id
     * @return int
     * @since 1.0
    */
    public static function lastId() {
        return mysqli_insert_id(self::$connection_id);
    }
	
    /**
     * Method to create an object for a single row
     * @return object
     * @since 1.0
    */
	public static function fetchObject( )
	{
	    return mysqli_fetch_object( self::$result );
	}
    
    /**
     * Method to create an array for a single row
     * @return array
     * @since 1.0
    */
	public static function fetchArray( )
	{
	    return mysqli_fetch_array( self::$result );
	}
	
	/**
     * Method to create an object for multiple rows
     * @return object
     * @since 1.0
    */
	public static function fetchObjectList()
	{
	    $object = array();

	    while ($row = self::fetchObject( self::$result )) {
	        $object[] = $row;
	    }
	    
	    self::free();
	    return $object;
	}

    /**
     * Method to return the number of affected rows
     * @return object
     * @since 1.0
    */
	public static function num_rows(  )
	{
	    return mysqli_num_rows( self::$result );
	}
    
    /**
     * Method to quote and optionally escape a string to database requirements for insertion into the database.
	 * @param   string   $text    The string to quote.
     * @param   boolean  $escape true if escape variable
	 * @return  string  The quoted input string.
	 * @since   1.0
	*/
	public static function quote($text)
	{
		if(is_numeric($text)) { return $text; } 
		return '\''.str_replace("'", "''", $text).'\'';
	}
	
    /**
     * Method to return the number of affected rows in the last query
     * @return int
     * @since 1.0
    */
  	public static function affected_rows(  )
  	{
    	return mysqli_affected_rows( );
  	}
      
    /**
     * Method to return a complete error report
     * @return string
     * @since 1.0
    */
    public static function getError()
    {
        return mysqli_errno(self::$connection_id)." : ".mysqli_error(self::$connection_id);
    }
    
    /**
     * Method to frees the memory associated with a result
    */
    public static function free()
    {
        return mysqli_free_result(self::$result);
    }
    
    /**
     * Method to close a connection
     * @since 1.0
    */
    public static function close()
    {
        return mysqli_close(self::$connection_id);
    }
}

?>
