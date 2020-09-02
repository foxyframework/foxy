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

$model 	= application::getModel($form);
$form = FOXY_COMPONENT.DS.'forms'.DS.'users.xml';
?>

<section class="forms container-fluid my-5">

    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Usuaris</h4>
          </div>
          <div class="card-body">
            <form method="post" action="index.php?task=users.saveItem">
              <?= html::getTextField($form, 'username'); ?>
              <?= html::getEmailField($form, 'email'); ?>
              <?= html::getPasswordField($form, 'password'); ?>
              <?= html::getUsergroupsField($form, 'usergroup'); ?>
              <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
</section>

<section class="container-fluid my-5">
  <div class="table-responsive">
    <table id="datatable1" style="width: 100%;" class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Usergroup</th>
          <th>Last Visit</th>
          <th>#</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($model->getList() as $usr) : ?>
        <tr>
          <td><?= $usr->username; ?></td>
          <td><?= $usr->email; ?></td>
          <td><?= $usr->level == 1 ? 'Admin' : 'Registered'; ?></td>
          <td><?= $usr->lastvisitDate; ?></td>
          <td><a href="index.php?task=users.removeItem&id=<?= $usr->id; ?>"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
