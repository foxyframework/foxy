<!DOCTYPE html>
<html class="h-100">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= config::$sitename; ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="<?= config::$description; ?>">
    <meta name="author" content="<?= config::$sitename; ?>">
    <?php
  	if(count(application::$metatags) > 0) :
  	foreach(application::$metatags as $tag) : ?>
  	<?= $tag. PHP_EOL; ?>
  	<?php endforeach;
  	endif;
  	?>
    <link rel="canonical" href="<?= url::selfUrl(); ?>">

    <?php if(settings::get('analytics') != '') : ?>
    <!-- Google Analytics -->
    <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', '<?= settings::get('analytics'); ?>', 'auto');
    ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->
    <?php endif; ?>

    <?php if(settings::get('recaptcha', 0) == 1 && application::getVar('view', 'home') == 'contact') : ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= settings::get('public_key'); ?>"></script>
    <script>
    grecaptcha.ready(function(){grecaptcha.execute('<?= settings::get('public_key'); ?>',{action: "contact"}).then(function(token){ document.getElementById('g-recaptcha-response').value = token; });});
    </script>
    <?php endif; ?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" integrity="sha512-thoh2veB35ojlAhyYZC0eaztTAUhxLvSZlWrNtlV01njqs/UdY3421Jg7lX0Gq9SRdGVQeL8xeBp9x1IPyL1wQ==" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="<?= config::$site; ?>/template/blog/css/custom.css">
    <link rel="stylesheet" href="<?= config::$site; ?>/template/system/css/foxy.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>

    <?php
  	if(count(application::$stylesheets) > 0) :
  	foreach(application::$stylesheets as $stylesheet) : ?>
  	<link href="<?= $stylesheet; ?>" rel="stylesheet">
  	<?php endforeach;
  	endif;
  	?>

    <link rel="apple-touch-icon-precomposed" href="<?= config::$site; ?>/assets/img/icons/icon64.png">
    <link rel="shortcut icon" href="<?= config::$site; ?>/assets/img/icons/icon16.png">
  </head>

  <body>

    <?= application::getModule('topmenu'); ?>
    <?php include('template/system/message.php'); ?>
    
    <main role="main"><?php @include(application::getLayout()); ?></main>

    <!-- Footer -->
    <footer class="container">
      <p>&copy; Company 2017-2020</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js" integrity="sha512-q2vREMvON/xrz1KuOj5QKWmdvcHtM4XNbNer+Qbf4TOj+RMDnul0Fg3VmmYprdf3fnL1gZgzKhZszsp62r5Ugg==" crossorigin="anonymous"></script>
    <?php
    if(count(application::$scripts) > 0) :
    foreach(application::$scripts as $script) : ?>
    <script src='<?= $script; ?>'></script>
    <?php endforeach;
    endif; ?>
    <script src="<?= config::$site; ?>/template/system/js/foxy.js"></script>
  </body>

</html>
