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

?>

<div class="page login-page">
  <div class="container">
    <div class="form-outer text-center d-flex align-items-center">
      <div class="form-inner w-100">
        <div class="logo text-uppercase px-5"><img src="assets/img/logo.png" class="img-fluid" alt="<?= config::$sitename; ?>"></div>
        <form action="<?= config::$site; ?>index.php?task=register.login" method="post" class="text-left form-validate">
          <input type="hidden" name="token" value="<?= application::getVar('token', ''); ?>">

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
  </div>
</div>