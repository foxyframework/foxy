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

//if user is logged or register isn't allowed in config...
if(user::getAuth() || config::$show_register == 0) {
  application::redirect(config::$site.'/index.php?view=home');
}

?>

 <div class="page login-page">
   <div class="container">
     <div class="form-outer text-center d-flex align-items-center">
       <div class="form-inner w-100">
         <div class="logo text-uppercase px-5"><img src="assets/img/logo.png" class="img-fluid" alt="<?= config::$sitename; ?>" style="width:65%"></div>
         <?php if(!user::getAuth()) : ?>
         <form action="index.php?task=register.register" method="post" data-toggle="validator" class="text-left form-validate">
           <?= html::getEmailField('register', 'email', ''); ?>
           <?= html::getPasswordField('register', 'password', ''); ?>
           <?= html::getPasswordField('register', 'password2', ''); ?>
           <?= html::getButton('register', 'submit'); ?>
           <p style="margin-top:5px;">
           <a href="index.php?view=register&layout=login" class="btn btn-success btn-block btn-lg">Login</a>
           </p>
           <?php else : ?>
           <a href="<?= config::$site; ?>/index.php?task=register.logout" class="btn btn-success btn-block"><?= language::get('FOXY_MENU_LOGOUT'); ?></a>
           <?php endif; ?>

         </form>
       </div>
     </div>
   </div>
 </div>
