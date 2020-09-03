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
$id   = application::getVar('id', 0, 'post');
$item = application::getModel('languages')->getItemById();
$form = FOXY_COMPONENT.DS.'forms'.DS.'languages.xml';
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-12">
 
                <form name="languagesForm" id="languagesForm" method="post" action="index.php?task=languages.saveLanguage">	
                    <input type="hidden" name="id" value="<?= $id; ?>">		
                    <?= html::getTextField($form, 'title', $item->title); ?>
                    <?= html::getTextField($form, 'code', $item->code); ?>
                    <?= html::getListField($form, 'status', $item->status); ?>
                    <div class="form-group">
                        <input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
                    </div>
                </form>
                
      		</div> 
        </div>
	</div>
</section>