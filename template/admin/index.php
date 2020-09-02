<?php $model = application::getModel(); ?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <link href="template/admin/css/admin.css" rel="stylesheet">
    <?php
  	if(count(application::$stylesheets) > 0) :
  	foreach(application::$stylesheets as $stylesheet) : ?>
  	<link href="<?= $stylesheet; ?>" rel="stylesheet">
  	<?php endforeach;
  	endif;
  	?>
</head>

<body>

    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    
                    <a class="navbar-brand" href="index.php?view=admin">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <img src="<?= config::$site; ?>assets/img/icons/icon32.png" alt="<?= config::$sitename; ?>" class="light-logo" />
                        </b>
                    </a>
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>

                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">

                    <ul class="navbar-nav float-right">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= user::$image; ?>" alt="<?= user::$username; ?>" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                            </div>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li>
                            <!-- User Profile-->
                            <div class="user-profile d-flex no-block dropdown m-t-20">
                                <div class="user-content hide-menu m-l-10">
                                    <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <h5 class="m-b-0 user-name font-medium"><?= user::$username; ?> <i class="fa fa-angle-down"></i></h5>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Userdd">
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>                                 
                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                    </div>
                                </div>
                            </div>
                            <!-- End User Profile-->
                        </li>
                        <!-- User Profile-->
                        <li class="sidebar-item"> 
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="index.php?view=admin" aria-expanded="false">
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <?php foreach($model->getAdminViews() as $view) : ?>
                        <li class="sidebar-item"> 
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="index.php?view=<?= $view; ?>&layout=admin" aria-expanded="false">
                                <span class="hide-menu"><?= ucfirst($view); ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <li class="text-center p-40 upgrade-btn">
                            <a href="#" class="btn btn-block btn-danger text-white" target="_blank">Store</a>
                        </li>
                    </ul>
                    
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

        <div class="page-wrapper">

            <?= application::getModule('breadcrumbs'); ?>
            <?php include('template/system/message.php'); ?>

            <?php @include(application::getLayout()); ?>
            
            <footer class="footer text-center">
            Foxy &copy; <?= date('Y'); ?> Made with using Foxy PHP Framework 1.1.0
            </footer>
            
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