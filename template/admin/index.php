<?php $model = application::getModel(); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= config::$sitename; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= config::$site; ?>assets/img/icons/icon16.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="<?= config::$site; ?>/template/system/css/foxy.css">
    <script src="https://kit.fontawesome.com/0deb70caff.js" crossorigin="anonymous"></script>

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


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

  </head>
  <body>
    
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#"><?= config::$sitename; ?></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
        <a class="nav-link" href="index.php?task=register.logout">Sign out</a>
        </li>
    </ul>
    </nav>

    <div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
            <?php foreach($model->getAdminViews() as $view) : ?>
                <li class="nav-item">
                <a class="nav-link" href="index.php?view=<?= $view; ?>&layout=admin" aria-expanded="false">
                    <span><?= ucfirst($view); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
            </ul>
        </div>
        </nav>

        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

            <?php include('template/system/message.php'); ?>
            <?php @include(application::getLayout()); ?>

        </main>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js" integrity="sha512-UzofO1xJCmOl9xNdbqkMIaaW5raQxAE8WyMa977+mY2fT001KydNwvqSTJlHy70edjCN0nb20BXIgBgO/oj6MQ==" crossorigin="anonymous"></script>
    <?php
    if(count(application::$scripts) > 0) :
    foreach(application::$scripts as $script) : ?>
    <script src='<?= $script; ?>'></script>
    <?php endforeach;
    endif; ?>
    <script src="<?= config::$site; ?>/template/system/js/foxy.js"></script>

  </body>
</html>