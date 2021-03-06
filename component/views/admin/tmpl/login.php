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

$form = FOXY_COMPONENT.DS.'forms'.DS.'login.xml';
?>

<section class="container marketing">
  <div class="row">
    <div class="col-12 col-md-6 m-auto">

      <form class="form-signin" action="<?= config::$site; ?>index.php?task=register.login" method="post" class="needs-validation">
          <img class="mb-4" src="assets/img/logo.png" alt="">
          <h1 class="h3 mb-3 font-weight-normal"><?= language::replace('FOXY_LOGIN_TITLE', config::$sitename); ?></h1>
          <input type="hidden" name="token" value="<?= application::getVar('token', ''); ?>">
          <input type="hidden" name="return" value="<?= application::getVar('return', ''); ?>">

          <?= html::getEmailField($form, 'email'); ?>
          <?= html::getPasswordField($form, 'password'); ?>
          <?= html::getTextField($form, 'lastvisitDate', date('Y-m-d H:i:s')); ?>
          <?= html::getTextField($form, 'language', 'en-gb'); ?>
          
          <div class="form-group text-center">
            <?= html::getButton($form, 'submit'); ?>
          </div>
      </form>

    </div>
  </div>
</section>