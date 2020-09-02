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
    public static $code = 'en-gb';

    /**
     * Constructor
    */
    public function __construct() 
    {
        if(user::getAuth() && !isset($_GET['lang'])) {
          self::$code = user::$language;
        }
        if(isset($_GET['lang'])){
          switch($_GET['lang']) {
            case 'ca':
              self::$code = 'ca-es'; 
              break;
            case 'es':
              self::$code = 'es-es';
              break;
            case 'en':
              self::$code = 'en-gb';
              break;
            default:
              self::$code = 'en-gb';
              break;
          }
        }
    }

    /**
     * Method to get a translatable string from the language file
     * @param string $text
     * @return string if success false if not
    */
    public static function get($text)
    {
        if(self::$code == "") { self::$code = 'en-gb'; }

        $file = FOXY_BASE.DS.'languages'.DS.self::$code.'.ini';

        if (file_exists($file) && is_readable($file))
        {
            $strings = parse_ini_file($file);
            $translation = @$strings[strtoupper($text)];
            if($translation != "") {
                return nl2br($translation);
            } else {
              $file = FOXY_BASE.DS.'languages'.DS.'en-gb.ini';
              $strings = parse_ini_file($file);
              $translation = @$strings[strtoupper($text)];
              if($translation != "") {
                  return nl2br($translation);
              } else {
                $view = application::getVar('view', '');
                if($view != '') {
                  $file = FOXY_COMPONENT.DS.'views'.DS.strtolower($view).DS.self::$code.'.'.strtolower($view).'.ini';
                  if (file_exists($file) && is_readable($file)) {
                    $strings = parse_ini_file($file);
                    $translation = @$strings[strtoupper($text)];
                    if($translation != "") {
                        return nl2br($translation);
                    } else {
                        return $text;
                    }
                  }
                }
              }
            }
        } else {
            return false;
        }
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
