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
$id   = application::getVar('id', 0, 'get');
$item = application::getModel('extensions')->getItemById();
$form = FOXY_COMPONENT.DS.'forms'.DS.'extensions.xml';
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-12">
 
                <form name="adminForm" id="adminForm" method="post" action="index.php?task=extensions.saveExtension">	
                    <input type="hidden" name="id" value="<?= $id; ?>">		
                    <?= html::getTextField($form, 'type', $item->type); ?>
                    <?= html::getTextField($form, 'folder', $item->folder); ?>
                    <?= html::getTextField($form, 'name', $item->name); ?>
                    <?= html::getListField($form, 'status', $item->status); ?>
                    <div class="form-group">
                        <input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
                    </div>
                </form>
                
      		</div> 
        </div>
	</div>
</section>