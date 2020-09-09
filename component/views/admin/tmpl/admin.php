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

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-12">
        <div class="row">
          <?php foreach($model->getAdminViews() as $view) : ?>
          <div class="col-3 pb-2">
            <a href="index.php?view=<?= $view; ?>&layout=admin">
            <div class="card">
              <div class="card-body text-center">
                <div><?= strtoupper($view); ?></div>
              </div>
            </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
