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
$form = FOXY_COMPONENT.DS.'forms'.DS.'blog.xml';
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-12">
 
                <form name="blogForm" id="blogForm" method="post" action="<?= config::$site; ?>index.php?task=blog.saveItem">
                    <input type="hidden" name="publishDate" value="<?= date('Y-m-d H:i:s'); ?>">			
                    <?= html::getTextField($form, 'title'); ?>
                    <?= html::getTextField($form, 'alias'); ?>
                    <?= html::getMediaField($form, 'image', 'demo'); ?>
                    <?= html::getTextField($form, 'tags'); ?>	
                    <?= html::getTextField($form, 'author'); ?>
                    <?= html::getTextField($form, 'author_link'); ?>		
                    <?= html::getEditorField($form, 'fulltext'); ?>	
                    <div class="form-group">
                        <input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
                    </div>
                </form>
                
      		</div> 
        </div>
	</div>
</section>