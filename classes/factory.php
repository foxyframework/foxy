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

abstract class factory
{
    /**
     * Method to get a instance of the custom class
     * @param class string the classname
     * @return object
    */
    public static function get($class)
    { 
      if($class == 'database') {
        $config = factory::get('config');
        $driver = $config->driver;
        $path = FOXY_CLASSES.DS.'database'.DS.$driver.'.php';
        if (file_exists($path)) 
        {
          include_once $path;
          $classname = $driver;
        } else {
          throw new Exception("The class is not loadable");
        }
      } else {
        $path = FOXY_CLASSES.DS.$class.'.php';
        if (file_exists($path))
        {
            include_once $path;
            $classname = ucfirst($class);
        } else {
          throw new Exception("The class is not loadable");
        }
      }

      $instance = new $classname();
      return $instance;
    }
}

?>
