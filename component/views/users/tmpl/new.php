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

$form = FOXY_COMPONENT.DS.'forms'.DS.'users.xml';
?>

<section class="forms container-fluid my-5">
    <div class="row">
      <div class="col-lg-6">

            <form method="post" action="<?= config::$site; ?>index.php?task=users.saveItem">
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
</section>