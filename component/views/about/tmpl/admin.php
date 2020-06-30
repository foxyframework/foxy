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

$params = $model->getParams();
$view   = application::getVar('view');
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-3">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Home</h4>
          </div>
          <div class="card-body">
            <p>Homepage settings.</p>
            <form method="post" action="index.php?task=admin.saveParams">
              <input type="hidden" name="view" value="<?= $view; ?>">
              <?= html::getListField('params', 'auth', $params->auth); ?>
              <?= html::getTextField('params', 'redirect', $params->redirect); ?>
              <?= html::getRadioField('params', 'fluid', $params->fluid); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
