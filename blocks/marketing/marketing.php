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

//$view = application::getVar('view');

?>

<section class="container marketing">

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading"><?= $_POST['arg1']; ?></h2>
        <p class="lead"><?= $_POST['arg2']; ?></p>
      </div>
      <div class="col-md-5">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="<?= $_POST['arg3']; ?>">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading"><?= $_POST['arg4']; ?></h2>
        <p class="lead"><?= $_POST['arg5']; ?></p>
      </div>
      <div class="col-md-5 order-md-1">
      <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="<?= $_POST['arg6']; ?>">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading"><?= $_POST['arg7']; ?></span></h2>
        <p class="lead"><?= $_POST['arg8']; ?></p>
      </div>
      <div class="col-md-5">
      <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="<?= $_POST['arg9']; ?>">
      </div>
    </div>

    <hr class="featurette-divider">

</section>