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

$model 	= application::getModel('menu');
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-3">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Menu</h4>
          </div>
          <div class="card-body">
            <p>Com administrador pots gestionar el menú principal.</p>
            <form method="post" action="index.php?task=users.saveMenuItem">
              <?= html::getTextField('menu', 'title'); ?>
              <?= html::getTextField('menu', 'translation'); ?>
              <?= html::getTextField('menu', 'url'); ?>
              <?= html::getListField('menu', 'auth'); ?>
              <?= html::getListField('menu', 'type'); ?>
              <?= html::getTextField('menu', 'module'); ?>
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

<section class="container-fluid">
	<?php
		$fields = ['id', 'title', 'translaton', 'url', 'auth', 'type', 'module'];
		$columns = ['Id', 'Title', 'Translation', 'Url', 'Auth', 'Type', 'Module'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns); ?>
</section>