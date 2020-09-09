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

$model  = application::getModel('settings');
$cfg    = $model->getSettings();
$form   = FOXY_COMPONENT.DS.'forms'.DS.'settings.xml';
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4><?= language::get('FOXY_SETTINGS_TITLE'); ?></h4>
          </div>
          <div class="card-body">
            <form id="adminForm" name="adminForm" method="post" action="<?= config::$site; ?>index.php?task=settings.saveSettings">
              <legend>Site</legend>
              <div class="row">
                <div class="col-12 col-md-6"><?= html::getListField($form, 'show_register', $cfg->show_register); ?></div>
                <div class="col-12 col-md-6"><?= html::getTextField($form, 'login_redirect', $cfg->login_redirect); ?></div>
              </div>
              <div class="row">
                <div class="col-12 col-md-6"><?= html::getListField($form, 'debug', $cfg->debug); ?></div>
                <div class="col-12 col-md-6"><?= html::getListField($form, 'offline', $cfg->offline); ?></div>
              </div>
              <div class="form-group">
                <input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
