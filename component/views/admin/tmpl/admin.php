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

$cfg    = $model->getConfig();
$cfg->show_register == '' ? $show_register = config::$show_register : $show_register = $cfg->show_register;
$cfg->login_redirect == '' ? $login_redirect = config::$login_redirect : $login_redirect = $cfg->login_redirect;
$cfg->debug == '' ? $debug = config::$debug : $debug = $cfg->debug;
$cfg->offline == '' ? $offline = config::$offline : $offline = $cfg->offline;
$form = FOXY_COMPONENT.DS.'forms'.DS.'admin.xml';
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Configuració global</h4>
          </div>
          <div class="card-body">
            <p>Com administrador pots configurar l'aplicació.</p>
            <form method="post" action="<?= $config->site; ?>index.php?task=admin.saveConfig">
              <?= html::getListField($form, 'show_register', $show_register); ?>
              <?= html::getTextField($form, 'login_redirect', $login_redirect); ?>
              <?= html::getListField($form, 'debug', $debug); ?>
              <?= html::getListField($form, 'offline', $offline); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
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
