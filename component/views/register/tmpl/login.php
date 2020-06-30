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

if(user::getAuth()) {
    application::redirect(config::$site);
}
?>

<section class="container marketing">
  <div class="row">
    <div class="col-12 col-md-6 m-auto">

      <form class="form-signin" action="<?= config::$sitesite; ?>/index.php?task=register.login" method="post" class="form-validate">
          <img class="mb-4" src="assets/img/logo.png" alt="">
          <h1 class="h3 mb-3 font-weight-normal">Please log in</h1>
          <input type="hidden" name="token" value="<?= $_GET['token']; ?>">

          <?= html::getEmailField('login', 'email'); ?>
          <?= html::getPasswordField('login', 'password'); ?>
          <?= html::getTextField('login', 'lastvisitDate', date('Y-m-d H:i:s')); ?>
          <?= html::getTextField('login', 'language', 'en-gb'); ?>
          
          <div class="form-group text-center">
            <?= html::getButton('login', 'submit'); ?>
          </div>
      </form>

    </div>
  </div>
</section>