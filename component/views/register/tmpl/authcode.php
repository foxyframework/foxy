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

<section class="container marketing">
  <div class="row my-5">
    <div class="col-12 col-md-6 m-auto">

        <h1>Please scan this</h1>

        <p>with the <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=es" target="_blank">Google Authenticator App</a></p>

        <p class="my-5">
            <?php $link = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate('Foxy', 'Foxy', $session->getVar('secret')); ?>
            <a  href="<?= $link; ?>"><img style="border: 0; padding:10px" src="<?= $link; ?>"/></a>
        </p>

        <form class="form-signin" action="<?= $config->site; ?>/index.php?task=register.login" method="post" class="form-validate">
            <div class="form-group">
                <input type="text" name="otp" value="" class="form-control">
            </div>
            <input type="hidden" name="tmpId" value="<?= $session->getVar('tmpid'); ?>">
            <div class="form-group text-center">
                <?= html::getButton('login', 'submit'); ?>
            </div>
      </form>

    </div>
  </div>
</section>