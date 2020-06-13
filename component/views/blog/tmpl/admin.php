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

if(!$user->getAuth()) {
	$app->redirect('index.php');
}

$model = $app->getModel();
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-6">
        		<div class="card">
          			<div class="card-header d-flex align-items-center">
						<h4>Blog</h4>
          			</div>
          			<div class="card-body">
						<p>Crea una nova entrada al blog.</p>
			
						<form name="blogForm" id="blogForm" method="post" action="index.php?task=blog.saveArticle">
							<?php $item->publishDate == '' ? $publishDate = date('Y-m-d H:i:s') : $publishDate = $item->publishDate; ?>
							<input type="hidden" name="publishDate" value="<?= $publishDate; ?>" />			
							<input type="hidden" name="id" value="<?= $item->id; ?>" />
							<?=  $html->getTextField('blog', 'title', $item->title); ?>
							<?=  $html->getTextField('blog', 'tags', $item->tags); ?>	
							<?=  $html->getTextField('blog', 'author', $item->author); ?>
							<?=  $html->getTextField('blog', 'author_link', $item->author_link); ?>		
							<?=  $html->getTextareaField('blog', 'fulltext', $item->fulltext, true); ?>	
						</form>
						<div class="form-group">
							<input type="submit" value="<?= $lang->get('FOXY_SAVE'); ?>" class="btn btn-primary">
						</div>
          			</div>
        		</div>
      		</div>       	
        </div>
	</div>
</section>
