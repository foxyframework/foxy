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
$form   = FOXY_COMPONENT.DS.'forms'.DS.'styles.xml';
$styles = application::getModel()->getStyles();
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-12">

            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4><?= language::get('FOXY_STYLES_TITLE'); ?></h4>
                </div>
                <div class="card-body">
                    <form id="adminForm" name="adminForm" method="post" action="<?= config::$site; ?>index.php?task=styles.compile">
                    <legend>Colors</legend>
                    <div class="row">
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'primary', $styles->primary); ?></div>
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'secondary', $styles->secondary); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'success', $styles->success); ?></div>
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'info', $styles->info); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'warning', $styles->warning); ?></div>
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'danger', $styles->danger); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'light', $styles->light); ?></div>
                        <div class="col-12 col-md-6"><?= html::getColorField($form, 'dark', $styles->dark); ?></div>
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
