<?php 
$model = application::getModel(); 
$view  = application::getVar('view');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= config::$sitename; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= config::$site; ?>assets/img/icons/icon16.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" integrity="sha512-thoh2veB35ojlAhyYZC0eaztTAUhxLvSZlWrNtlV01njqs/UdY3421Jg7lX0Gq9SRdGVQeL8xeBp9x1IPyL1wQ==" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= config::$site; ?>template/admin/css/admin.css"> 
    <link rel="stylesheet" href="<?= config::$site; ?>template/system/css/foxy.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>

    <?php
  	if(count(application::$metatags) > 0) :
  	foreach(application::$metatags as $tag) : ?>
  	<?= $tag. PHP_EOL; ?>
  	<?php endforeach;
  	endif;
  	?>

    <?php
  	if(count(application::$stylesheets) > 0) :
  	foreach(application::$stylesheets as $stylesheet) : ?>
  	<link href="<?= $stylesheet; ?>" rel="stylesheet">
  	<?php endforeach;
  	endif;
  	?>

  </head>
  <body>
    
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#"><?= config::$sitename; ?></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav px-3" style="flex-direction:initial;">
        <li class="nav-item text-nowrap">
          <a class="nav-link" target="_blank" href="index.php?view=home">Site</a>
        </li>
        <?php if(user::getAuth()) : ?>
        <li class="nav-item text-nowrap px-3">
        <a class="nav-link" href="index.php?task=register.logout">Sign out</a>
        </li>
        <?php endif; ?>
    </ul>
    </nav>

    <div class="container-fluid">
    <div class="row">
        <?php if(user::getAuth()) : ?>
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link <?php if($view == 'admin') : ?>active<?php endif; ?>" href="index.php?view=admin" aria-expanded="false">
                <span>Dashboard</span>
              </a>
            </li>
            <?php foreach($model->getAdminViews() as $page) : ?>
                <li class="nav-item <?php if($view == $page) : ?>active<?php endif; ?>">
                  <a class="nav-link" href="index.php?view=<?= $page; ?>&layout=admin" aria-expanded="false">
                      <span><?= ucfirst($page); ?></span>
                  </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        </nav>
        <?php endif; ?>

            <main class="<?php if(user::getAuth()) : ?>col-md-9 ml-sm-auto col-lg-10<?php else: ?>col-12<?php endif; ?> px-md-4">

            <?php include('template/system/message.php'); ?>
            <?php @include(application::getLayout()); ?>

        </main>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js" integrity="sha512-q2vREMvON/xrz1KuOj5QKWmdvcHtM4XNbNer+Qbf4TOj+RMDnul0Fg3VmmYprdf3fnL1gZgzKhZszsp62r5Ugg==" crossorigin="anonymous"></script>
    <?php
    if(count(application::$scripts) > 0) :
    foreach(application::$scripts as $script) : ?>
    <script src='<?= $script; ?>'></script>
    <?php endforeach;
    endif; ?>
    <script src="<?= config::$site; ?>/template/system/js/admin.js"></script>

  </body>
</html>