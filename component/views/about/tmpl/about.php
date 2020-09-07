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

$params = $model->getParams('about');
?>

<section class="container marketing">

  <div class="row featurette">
    <div class="col-12">
      <hr class="featurette-divider">
      <h3>About <?= $config->sitename; ?></h3>
    </div>
  </div>

  <div class="row featurette">
  <div class="col-12">
      <hr class="featurette-divider">
      <h3>License</h3>
      <p><?= $config->sitename; ?> is an Open Source project writted under the <a href="https://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU GPL v.3</a> license.</p>
    </div>
  </div>

  <div class="row featurette">
    <div class="col-12">
      <hr class="featurette-divider">
      <h3>Description</h3>
      <p><?= $config->description; ?></p>
      <hr class="featurette-divider">
    </div>
  </div>

</section>
