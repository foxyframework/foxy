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
						<form name="blogForm" id="blogForm" method="post" action="index.php?task=blog.saveItem">
							<?php $item->publishDate == '' ? $publishDate = date('Y-m-d H:i:s') : $publishDate = $item->publishDate; ?>
							<input type="hidden" name="publishDate" value="<?= $publishDate; ?>">			
							<input type="hidden" name="id" value="<?= application::getVar('id', 0); ?>">
							<?=  html::getTextField('blog', 'title', $item->title); ?>
							<?=  html::getTextField('blog', 'alias', $item->alias); ?>
							<?=  html::getMediaField('blog', 'image', 'demo'); ?>
							<?=  html::getTextField('blog', 'tags', $item->tags); ?>	
							<?=  html::getTextField('blog', 'author', $item->author); ?>
							<?=  html::getTextField('blog', 'author_link', $item->author_link); ?>		
							<?=  html::getEditorField('blog', 'fulltext', $item->fulltext); ?>	
							<div class="form-group">
								<input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
							</div>
						</form>
          			</div>
        		</div>
      		</div> 
			<div class="col-lg-6">
       	 		<div class="card">
          			<div class="card-header d-flex align-items-center">
            			<h4>Settings</h4>
          			</div>
          			<div class="card-body">
            			<form method="post" action="index.php?task=admin.saveParams">
              				<input type="hidden" name="view" value="blog">
							<?= html::getListField('params', 'auth', $params->auth); ?>
							<?= html::getTextField('params', 'redirect', $params->redirect); ?>
							<?= html::getRadioField('params', 'fluid', $params->fluid); ?>
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

<section class="container-fluid my-5">
	<?php
		$fields = ['title', 'author', 'publishDate'];
		$columns = ['Title', 'Author', 'Publish Date'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns); ?>
</section>
