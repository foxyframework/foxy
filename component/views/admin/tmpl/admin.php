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

$model 	= $app->getModel('admin');
$cfg    = $model->getConfig();
$cfg->show_register == '' ? $show_register = $config->show_register : $show_register = $cfg->show_register;
$cfg->login_redirect == '' ? $login_redirect = $config->login_redirect : $login_redirect = $cfg->login_redirect;
$cfg->debug == '' ? $debug = $config->debug : $debug = $cfg->debug;
$cfg->offline == '' ? $offline = $config->offline : $offline = $cfg->offline;
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Configuració global</h4>
          </div>
          <div class="card-body">
            <p>Com administrador pots configurar l'aplicació.</p>
            <form method="post" action="<?= $config->site; ?>index.php?task=admin.saveConfig">
              <?= $html->getListField('admin', 'show_register', $show_register); ?>
              <?= $html->getTextField('admin', 'login_redirect', $login_redirect); ?>
              <?= $html->getListField('admin', 'debug', $debug); ?>
              <?= $html->getListField('admin', 'offline', $offline); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="row">
          <?php foreach($model->getAdminViews() as $view) : ?>
          <div class="col-2">
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
