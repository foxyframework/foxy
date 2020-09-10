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
$id = application::getVar('id', 0, 'get', 'int');
$params_form = FOXY_COMPONENT.DS.'forms'.DS.'params_blog.xml';
$model  = application::getModel('blog');
$params = $model->getParams($id);
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
			<div class="col-lg-12">

                <form method="post" action="<?= config::$site; ?>index.php?task=blog.saveParams">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <input type="hidden" name="view" value="blog">
                    <?= html::getListField($params_form, 'show_author', $params->show_author); ?>
                    <?= html::getListField($params_form, 'show_date', $params->show_date); ?>
                    <?= html::getListField($params_form, 'show_hits', $params->show_hits); ?>
                    <?= html::getListField($params_form, 'show_tags', $params->show_tags); ?>
                    <?= html::getListField($params_form, 'show_readmore', $params->show_readmore); ?>
                    <div class="form-group">
                        <input type="submit" value="Guardar" class="btn btn-primary">
                    </div>
                </form>

      		</div>      	
        </div>
	</div>
</section>