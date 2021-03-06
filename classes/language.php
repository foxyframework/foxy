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

class Language
{
    public static $code = 'es-es';

    /**
     * Method to get the language active
     * @return string active language
    */
    public static function getActive()
    {
        database::query('SELECT code FROM `#_languages` WHERE status = 1 ORDER BY ordering DESC');
        return database::fetchArray();
    }

    /**
     * Method to get a translatable string from the language file
     * @param string $text
     * @return string if success false if not
    */
    public static function get($text)
    {
        if(isset($_COOKIE['lang'])) { self::$code = $_COOKIE['lang']; }

        $view = application::getVar('view');

        $file = FOXY_BASE.DS.'languages'.DS.self::$code.'.ini';

        if (file_exists($file) && is_readable($file))
        {
            $strings = parse_ini_file($file);
            $translation = @$strings[strtoupper($text)];
            if($translation != "") {
              return nl2br($translation);
            }
        } 

        $file_view = FOXY_COMPONENT.DS.'views'.DS.strtolower($view).DS.'languages'.DS.self::$code.'.'.strtolower($view).'.ini';
        
        if(file_exists($file_view) && is_readable($file_view)) {
            $strings = parse_ini_file($file_view);
            $translation = @$strings[strtoupper($text)];
            if($translation != "") {
              return nl2br($translation);
            }
        }
          
        return $text;
    }

    /**
     * Method to get a translatable string from the language file and sprintf arguments
     * @param string $text
     * @param mixed args you can pass unlimited number of arguments to complete the string
     * @see http://www.php.net/manual/en/function.sprintf.php for especifications
     * @return string if success false if not
    */
    public static function replace($text)
    {
        $args = @func_get_args();
        $count = count($args);
        if ($count > 0)
        {
            $args[0] = self::get($text);
            return call_user_func_array('sprintf', $args);
        }
    }
}

?>
