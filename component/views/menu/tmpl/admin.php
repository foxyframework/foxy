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
            <form method="post" action="index.php?task=menu.saveMenuItem">
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

<section>
  <div class="container-fluid">
    <!-- Page Header-->
    <div class="card">
      <div class="card-header">
        <h4>Items</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="datatable1" style="width: 100%;" class="table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Translation</th>
                <th>Url</th>
                <th>Auth</th>
                <th>Type</th>
                <th>Module</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($model->getMenuItems() as $mi) : ?>
              <tr>
                <td><?= $mi->id; ?></td>
                <td><?= $mi->title; ?></td>
                <td><?= $mi->translation; ?></td>
                <td><?= $mi->url; ?></td>
                <td><?= $mi->auth; ?></td>
                <td><?= $mi->type; ?></td>
                <td><?= $mi->module; ?></td>
                <td><a href="index.php?task=menu.removeMenuItem&id=<?= $usr->id; ?>"><i class="fa fa-trash-o"></i></a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
