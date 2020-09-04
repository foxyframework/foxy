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

class Application
{
    /**
     * Array of scripts placed in the header
     *
     * @var  array
     * @access   private
    */
    public static $scripts = array();

    /**
     * Array of scripts placed in the header
     *
     * @var  array
     * @access   private
    */
    public static $scriptCode = array();

    /**
     * Array of stylesheets placed in the header
     *
     * @var  array
     * @access   private
    */
    public static $stylesheets = array();

    /**
     * Array of metatags placed in the header
     *
     * @var  array
     * @access   private
    */
    public static $metatags = array();

    /**
     * View
     *
     * @var     string
     * @access  private
    */
    public static $view = '';

    /**
     * Layout
     *
     * @var     string
     * @access  private
    */
    public static $layout = '';

    /**
     * Task
     *
     * @var     string
     * @access  private
    */
    public static $task = '';

    /**
     * Var
     *
     * @var     mixed
     * @access  private
    */
    public static $var = "";

    /**
     * Adds a linked script to the page
     *
     * @param    string  $url        URL to the linked script
     * @param    string  $type        Type of script. Defaults to 'text/javascript'
     * @access   public
     */
    public static function addScript($url) {
        self::$scripts[] = $url;
    }

    /**
     * Adds a javascript code to the page
     *
     * @param    string  $code        code string
     * @param    string  $type        Type of script. Defaults to 'text/javascript'
     * @access   public
     */
    public static function addScriptCode($code) {
        self::$scriptCode[] = $code;
    }

    /**
     * Adds a linked stylesheet to the page
     *
     * @param    string  $url        URL to the linked stylesheet
     * @access   public
     */
    public static function addStylesheet($url) {
        self::$stylesheets[] = $url;
    }

    /**
     * Adds custom metatags to the page
     *
     * @param    string  $tag       Tag to insert
     * @access   public
     */
    public static function addCustomTag($tag) {
        self::$metatags[] = $tag;
    }

    /**
     * Adds a linked stylesheet to the page
     *
     * @param    string  $url    URL to the linked style sheet
     * @param    string  $type   Mime encoding type
     * @param    string  $media  Media type that this stylesheet applies to
     * @access   public
     */
    public static function setMessage($msg, $type)
    {
        $_SESSION['message'] = $msg;
        $_SESSION['messageType'] = $type;
    }

    /**
     * Method to get application version
     * @access   public
    */
    public static function getVersion()
    {
        $local  = json_decode(file_get_contents(FOXY_BASE.DS.'foxy.json'), true);

    	return $local['version'];
    }

    /**
     * Method to encrypt passwords
     *
     * @param    string  $password   raw password to hash
     * @access   public
    */
    public static function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Method to decrypt passwords
     * 
     * @param    string  $password    hashed password
     * @param    string  $hash        Hash to compare with
     * @access   public
    */
    public static function decryptPassword($password, $hash)
    {
      if (password_verify($password, $hash)) {
			  return true;
  		} else {
  			return false;
  		}
    }

    /**
     * Method to trigger plugins
     * 
     * @param    string  $type    name of the plugin
     * @param    array   $args    Array of parameters to send
     * @access   public
    */
    public static function trigger($type, $args=array())
    {
    	$path = FOXY_PLUGINS.DS.$type.DS.$type.'.php';

  		if (file_exists($path))
  		{
  			include_once $path;
  			$type::execute($args);
  		}
    }

    /**
     * Method to render html blocks
     * 
     * @param    string  $type    name of the html block
     * @access   public
    */
    public static function render($menuId)
    {
        $lang = application::getVar('lang', 'en-gb', 'cookie');
        database::query('SELECT * FROM `#_blocks` WHERE pageId = '.$menuId.' AND language = '.database::quote($lang).' ORDER BY ordering DESC');
        $rows = database::fetchObjectList();

        $html  = '';

  		if (count($rows) > 0)
  		{             
            foreach($rows as $row) {

                $params = json_decode($row->params);
                $blockpath = FOXY_BASE.DS.'blocks'.DS.strtolower($row->title).DS.strtolower($row->title).'.html';
                
                if(file_exists($blockpath)) {
                    $block = file_get_contents($blockpath);
                }

                //get page params
                $config   = FOXY_COMPONENT.DS.'views'.DS.$view.DS.'params.json';
                $cfg      = json_decode(file_get_contents($path));
                        
                if(file_exists($config)) {
                    $cfg->fluid == 0 ? $container = 'container' : $container = 'container-fluid';
                    $block = str_replace('{container}', $container, $block);
                }
                  
                foreach($params as $k => $v) {
                    $block = str_replace('{'.$k.'}', $v, $block);
                }

                $html .= $block;

            }
        }
          
        return $html;
    }

    /**
     * Fetches and returns a given variable.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * an entry from $_GET, POST and PUT will result in returning an
     * entry from $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     * post    $_POST
     * get     $_GET
     * files   $_FILES
     * cookie  $_COOKIE
     * env     $_ENV
     * server  $_SERVER
     * method  via current $_SERVER['REQUEST_METHOD']
     * default $_REQUEST
     *
     *  You can force the type of variable
     *
     * (int), (integer) - forzado a integer
     * (bool), (boolean) - forzado a boolean
     * (float), (double), (real) - forzado a float
     * (string) - forzado a string
     * (array) - forzado a array
     * (object) - forzado a object
     * (unset) - forzado a NULL (PHP 5)
     *
     * @param   string   $name     Variable name.
     * @param   string   $default  Default value if the variable does not exist.
     * @param   string   $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @param   string   $type     Return type for the variable (int,string).
     *
     * @return  mixed  Requested variable.
     */
    public static function getVar($name, $default = null, $hash = 'REQUEST', $type = 'none')
    {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);

        // Get the input hash
        switch ($hash)
        {
            case 'GET':
                $input = &$_GET;
                break;
            case 'POST':
                $input = &$_POST;
                break;
            case 'FILES':
                $input = &$_FILES;
                break;
            case 'COOKIE':
                $input = &$_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                break;
        }

        if (array_key_exists($name, $input)) {
			$var = $input[$name];
		}

        //set default value
        if(empty($var)) {
            $var = $default;
        }

        //force type
        switch ($type)
        {
            case 'int':
                $var = (int)$var;
                break;
            case 'bool':
                $var = (bool)$var;
                break;
            case 'float':
                $var = (float)$var;
                break;
            case 'string':
                $var = (string)$var;
                break;
            default:
                $var = $var;
                break;
        }
        return $var;
    }

    /**
     * Method to load a layout
     * 
     * @access   public
    */
    public static function getLayout()
    {
        self::$task     = application::getVar('task', null, 'get', 'string');
        self::$view     = application::getVar('view', 'home', 'get', 'string');
        self::$layout   = application::getVar('layout', null, 'get', 'string');

        if(config::$offline == 1 && (!user::getAuth() && user::$level > 1)) { return 'offline.php'; }

        //check permissions and redirect if not authenticated...
        $path   = FOXY_COMPONENT.DS.'views'.DS.self::$view.DS.'params.json';
        $params = json_decode(file_get_contents($path));
        if(file_exists($path)) {
            if($params->auth == 1 && !user::getAuth()) {
                self::redirect($params->redirect);
                return false;
            }
        }

        if(self::$task != null) {
            if(strpos(self::$task, ".") !== false) {
                //task contains the model and the task separated by a dot
                $parts = explode('.', self::$task);
                self::$view = $parts[0];
                self::$task = $parts[1];
            }
            $model = self::getModel();
            $model->{self::$task}();
        } else {

            $path = FOXY_COMPONENT.DS.'views'.DS.self::$view.DS.'tmpl'.DS.self::$view.'.php';
        
            if(self::$layout != null) {
                $path = FOXY_COMPONENT.DS.'views'.DS.self::$view.DS.'tmpl'.DS.self::$layout.'.php';
            }

            if (is_file($path)) {
                return $path;
            }  else {
                http_response_code(404);
                return 'error.php';
            }

        }
    }

    /**
     * Method to load the view model
     * @param $model string call to specific model
     * @return object
    */
    public static function getModel($model = null)
    {
        $model == null ? $path  = FOXY_COMPONENT.DS.'models'.DS.self::$view.'.php' : $path = FOXY_COMPONENT.DS.'models'.DS.$model.'.php';
        $model == null ? $class = self::$view : $class = $model;
        $instance = "";

		if (file_exists($path))
		{
			include_once $path;
			if (class_exists($class)) {
			    $instance = new $class;
			}
		}
        return $instance;
    }

    /**
     * Method to load a module
     * @access public
     * @return boolean, return module output
    */
    public static function getModule($name)
    {
        $html = "";
        $path = FOXY_MODULES.DS.$name.DS.'default.php';
        if (is_file($path)) {
            ob_start();
			include_once $path;
			$html = ob_get_clean();
       	}
        return $html;
    }

    /**
     * Method to load a view
     * @access public
     * @return boolean, return view output
    */
    public static function getView()
    { 
        self::$view = application::getVar('view', 'home', 'get', 'string');
        self::$layout = application::getVar('layout', '', 'get', 'string');

        $path = FOXY_COMPONENT.DS.'views'.DS.self::$view.DS.'view.php';

        if (is_file($path)) {
        	return $path;
        }
    }

    /**
  	 * Method to render the view.
  	 *
  	 * @return  string  The rendered view.
  	 *
  	 * @since   1.2.0
  	 * @throws  RuntimeException
  	*/
  	public static function renderView($view, $layout='', $args=array())
  	{
  		// Get the layout path.
        $path = FOXY_COMPONENT.DS.'views'.DS.$view.DS.'tmpl'.DS.$view.'.php';

        if($layout != '') {
            $path = FOXY_COMPONENT.DS.'views'.DS.$view.DS.'tmpl'.DS.$layout.'.php';
        }

  		// Check if the layout path was found.
  		if (!is_file($path)) {
  			throw new RuntimeException('Layout Path Not Found');
        }
          
        if(count($args) > 0) {
            foreach($args as $k => $v) {
                $_POST[$k] = $v;
            }
        }

  		// Start an output buffer.
  		ob_start();

  		// Load the layout.
  		include $path;

  		// Get the layout contents.
  		$output = ob_get_clean();

  		return $output;
  	}

    /**
     * Method to load the template
     * @param $tmpl string call to specific template
     * @return string
    */
    public static function getTemplate()
    {
        self::$view == 'admin' || self::$layout == 'admin' ? $tmpl = 'admin' : $tmpl = config::$template;
        $mode = application::getVar('mode', '');
        if($mode == 'raw') {
            $path = FOXY_TEMPLATES.DS.'system'.DS.'index2.php';
        } else {
            $path = FOXY_TEMPLATES.DS.$tmpl.DS.'index.php';
        }
        if (is_file($path)) {
            return $path;
        }  else {
            http_response_code(404);
            return 'error.php';
        }
    }

    /**
     * Method to redirect to other url
     * @param $url string
    */
    public static function redirect($url, $message='', $typeError='')
    {
        if($message != '') {
            application::setMessage($message, $typeError);
        }
        /*
         * If the headers have been sent, then we cannot send an additional location header
         * so we will output a javascript redirect statement.
         */
        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>\n";
        } else {
            //@ob_end_clean(); // clear output buffer
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( 'Location: ' . $url );
        }
    }
}
?>
