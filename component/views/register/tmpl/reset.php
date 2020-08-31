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
$form = FOXY_COMPONENT.DS.'forms'.DS.'reset.xml';
?>

<div class="page login-page">
  <div class="container">
    <div class="form-outer text-center d-flex align-items-center">
      <div class="form-inner w-100">
        <div class="logo text-uppercase px-5"><img src="assets/img/logo.png" class="img-fluid" alt="<?= config::$sitename; ?>" style="width:65%"></div>
        <p><?= language::get('CW_RESET_DESC'); ?></p>
        <form action="<?= config::$site; ?>/index.php?task=register.reset" method="post" class="text-left needs-validation">
          <?= html::getEmailField($form, 'email'); ?>
          <div class="form-group text-center">
            <button type="submit" id="resetBtn" class="btn btn-success"><?= language::get('FOXY_SEND'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
