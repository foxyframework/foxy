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

class Url
{
    /**
     * @access public
     * @param url string Not fiendly url
     * @return a friendly url
    */
    public static function genUrl($url)
    {
        if(strpos($url, 'task') !== false) { return config::$site.$url; }
        if(strpos($url, 'raw') !== false) { return config::$site.$url; }
            
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);
        $url = implode('/', $params);
        $site = config::$site;
        if(substr($site, -1) == '/') {
            return config::$site.$url.'.html';
        } else {
            return config::$site.DS.$url.'.html';
        }
    }

    public static function getDomain()
    {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL;
    }
    
    /**
     * @access public
     * @return a the present url
    */
    public static function selfUrl()
    {
        $pageURL = "//";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}
