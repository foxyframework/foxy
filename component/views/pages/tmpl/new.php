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

$model 	= application::getModel($form);
$form = FOXY_COMPONENT.DS.'forms'.DS.'pages.xml';

?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-3">
      <div class="col-12">

            <form method="post" action="<?= config::$site; ?>index.php?task=pages.savePage">
              <?= html::getTextField($form, 'title'); ?>
              <?= html::getTextField($form, 'translation'); ?>
              <?= html::getListField($form, 'url'); ?>
              <?= html::getListField($form, 'auth'); ?>
              <?= html::getListField($form, 'type'); ?>
              <?= html::getTextField($form, 'module'); ?>
              <?= html::getListField($form, 'inMenu'); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>

      </div>

    </div>
  </div>
</section>