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

session_name('Foxy_'.date('d'));
session_start();
define('_Foxy', 1);
error_reporting(E_ALL ^ E_NOTICE);

date_default_timezone_set('Europe/Berlin');
define('FOXY_BASE', dirname(__FILE__) );
define('DS', DIRECTORY_SEPARATOR );

require_once(FOXY_BASE.DS.'includes/defines.php');
require_once(FOXY_CLASSES.DS.'autoloader.php');
require_once(FOXY_VENDOR.DS.'autoload.php');

if(config::$offline == 0) {
  $app->redirect(config::$site);
}

$reg = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-IN');
$reg = json_decode($reg);
$bg  = $reg->images[0]->url;
?>

<!DOCTYPE html>
<html class="h-100">
  <head>
    <meta charset="utf-8" />
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= config::$sitename; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="description" content="<?= config::$description; ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="<?= config::$site; ?>/template/<?= config::$template; ?>/css/custom.css">

    <link rel="apple-touch-icon-precomposed" href="<?= config::$site; ?>/assets/img/icons/icon64.jpg">
    <link rel="shortcut icon" href="<?= config::$site; ?>/assets/img/icons/icon16.jpg">

  </head>

  <body>

  <main role="main" class="flex-shrink-0">
    <div class="page login-page">
      <div class="container">
        <div class="form-outer text-center d-flex align-items-center">
          <div class="form-inner w-100">
            <div class="logo text-uppercase px-5"><img src="assets/img/logo.png" class="img-fluid" alt="<?= config::$sitename; ?>" style="width:65%"></div>
            <p><?= config::$sitename; ?> està offline per tasques de manteniment. Torneu en uns minuts, gràcies!</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  </body>
</html>
