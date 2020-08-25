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

$model 	= application::getModel('pages');
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Pages</h4>
          </div>
          <div class="card-body">
            <form method="post" action="index.php?task=pages.savePage">
              <?= html::getTextField('pages', 'title'); ?>
              <?= html::getTextField('pages', 'translation'); ?>
              <?= html::getTextField('pages', 'url'); ?>
              <?= html::getListField('pages', 'auth'); ?>
              <?= html::getListField('pages', 'type'); ?>
              <?= html::getTextField('pages', 'module'); ?>
              <?= html::getListField('pages', 'inMenu'); ?>
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