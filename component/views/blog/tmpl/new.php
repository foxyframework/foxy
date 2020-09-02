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
                    <?php $item->publishDate == '' ? $publishDate = date('Y-m-d H:i:s') : $publishDate = $item->publishDate; ?>
                    <input type="hidden" name="publishDate" value="<?= $publishDate; ?>">			
                    <input type="hidden" name="id" value="<?= application::getVar('id', 0); ?>">
                    <?=  html::getTextField($form, 'title', $item->title); ?>
                    <?=  html::getTextField($form, 'alias', $item->alias); ?>
                    <?=  html::getMediaField($form, 'image', 'demo', $item->image); ?>
                    <?=  html::getTextField($form, 'tags', $item->tags); ?>	
                    <?=  html::getTextField($form, 'author', $item->author); ?>
                    <?=  html::getTextField($form, 'author_link', $item->author_link); ?>		
                    <?=  html::getEditorField($form, 'fulltext', $item->fulltext); ?>	
                    <div class="form-group">
                        <input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
                    </div>
                </form>
                
      		</div> 
        </div>
	</div>
</section>