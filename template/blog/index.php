<!DOCTYPE html>
<html class="h-100">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $config->sitename; ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="<?= $config->description; ?>">
    <meta name="author" content="<?= $config->sitename; ?>">
    <link rel="canonical" href="<?= $url->selfUrl(); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="<?= $config->site; ?>/template/blog/css/custom.css">
    <link rel="stylesheet" href="<?= $config->site; ?>/template/system/css/foxy.css">
    <script src="https://kit.fontawesome.com/0deb70caff.js" crossorigin="anonymous"></script>

    <?php
  	if(count($app->stylesheets) > 0) :
  		foreach($app->stylesheets as $stylesheet) : ?>
  		    <link href="<?= $stylesheet; ?>" rel="stylesheet">
  		<?php endforeach;
  	endif;
  	?>

    <link rel="apple-touch-icon-precomposed" href="<?= $config->site; ?>/assets/img/icons/icon64.png">
    <link rel="shortcut icon" href="<?= $config->site; ?>/assets/img/icons/icon16.png">
  </head>

  <body>

    <?= $app->getModule('topmenu'); ?>
    <?php include('template/system/message.php'); ?>
    
    <main role="main"><?php @include($app->getLayout()); ?></main>

    <!-- Footer -->
    <footer class="container">
      <p>&copy; Company 2017-2020</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"> </script>
    <?php
    if(count($app->scripts) > 0) :
    foreach($app->scripts as $script) : ?>
    <script src='<?= $script; ?>'></script>
    <?php endforeach;
    endif; ?>
    <script src="<?= $config->site; ?>/template/system/js/foxy.js"></script>
  </body>

</html>
