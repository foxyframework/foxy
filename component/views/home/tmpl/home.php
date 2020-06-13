<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Foxy') or die ('restricted access');
$model = $app->getModel('admin');
$params = $model->getParams();
?>

<section class="jumbotron text-center">
  <div class="container">
    <h1>Foxy PHP Framework</h1>
    <p>A small PHP Framework for rapid development of web applications</p>
    <img src="assets/img/icons/icon264.png">
  </div>
</section>

<section class="container<?php if($params->fluid == 1): ?>-fluid<?php endif; ?> marketing">

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">Light as a feather. <span class="text-muted">Lightweight.</span></h2>
        <p class="lead"><?= $config->sitename; ?> weighs very little, the entire package once uploaded to your server is only 916Kb.</p>
      </div>
      <div class="col-md-5">
        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="assets/img/demo/fox1.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading">Extend the code. <span class="text-muted">Plugins.</span></h2>
        <p class="lead"><?= $config->sitename; ?> allows you to create the pages you want but also extend the functionality with small pieces of code called modules and plugins.</p>
      </div>
      <div class="col-md-5 order-md-1">
      <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="assets/img/demo/fox2.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">Speak to the world. <span class="text-muted">Internationalization.</span></h2>
        <p class="lead"><?= $config->sitename; ?> allows you to create files with the translation of the text strings that you have in your application..</p>
      </div>
      <div class="col-md-5">
      <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="assets/img/demo/fox3.jpg">
      </div>
    </div>

    <hr class="featurette-divider">

  </section>