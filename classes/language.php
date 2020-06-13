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
    public $code = 'en-gb';

    /**
     * Constructor
    */
    public function __construct() 
    {
        $user = factory::get('user');

        if($user->getAuth() && !isset($_GET['lang'])) {
            $this->code = $user->language;
        }
        if(isset($_GET['lang'])){
          switch($_GET['lang']) {
            case 'ca':
              $this->code = 'ca-es'; 
              break;
            case 'es':
              $this->code = 'es-es';
              break;
            case 'en':
              $this->code = 'en-gb';
              break;
            default:
              $this->code = 'en-gb';
              break;
          }
        }
    }

    /**
     * Method to get a translatable string from the language file
     * @param string $text
     * @return string if success false if not
    */
    public function get($text)
    {
        if($this->code == "") { $this->code = 'en-gb'; }

        $file = 'languages/'.$this->code.'.ini';

        if (file_exists($file) && is_readable($file))
        {
            $strings = parse_ini_file($file);
            $translation = @$strings[strtoupper($text)];
            if($translation != "") {
                return nl2br($translation);
            } else {
              $file = 'languages/en-gb.ini';
              $strings = parse_ini_file($file);
              $translation = @$strings[strtoupper($text)];
              if($translation != "") {
                  return nl2br($translation);
              } else {
                  return $text;
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
    public function replace($text)
    {
        $args = @func_get_args();
        $count = count($args);
        if ($count > 0)
        {
            $args[0] = $this->get($text);
            return call_user_func_array('sprintf', $args);
        }
    }
}

?>
