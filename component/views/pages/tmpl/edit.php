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
$id   = application::getVar('id', 0, 'get');
$item = application::getModel('pages')->getItemById();
$form = FOXY_COMPONENT.DS.'forms'.DS.'pages.xml';

?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-3">
      <div class="col-12">

            <form method="post" action="<?= config::$site; ?>index.php?task=pages.savePage">
              <input type="hidden" name="id" value="<?= $id; ?>">
              <?= html::getTextField($form, 'title', $item->title); ?>
              <?= html::getTextField($form, 'translation', $item->translation); ?>
              <?= html::getListField($form, 'url', $item->url); ?>
              <?= html::getListField($form, 'auth', $item->auth); ?>
              <?= html::getListField($form, 'type', $item->type); ?>
              <?= html::getTextField($form, 'module', $item->module); ?>
              <?= html::getListField($form, 'inMenu', $item->inMenu); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>

      </div>

    </div>
  </div>
</section>