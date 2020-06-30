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
							<input type="hidden" name="id" value="<?= $app->getVar('id', 0); ?>">
							<?=  html::getTextField('blog', 'title', $item->title); ?>
							<?=  html::getTextField('blog', 'alias', $item->alias); ?>
							<?=  html::getTextField('blog', 'tags', $item->tags); ?>	
							<?=  html::getTextField('blog', 'author', $item->author); ?>
							<?=  html::getTextField('blog', 'author_link', $item->author_link); ?>		
							<?=  html::getEditorField('blog', 'fulltext', $item->fulltext); ?>	
							<div class="form-group">
								<input type="submit" value="<?= $lang->get('FOXY_SAVE'); ?>" class="btn btn-primary">
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
	<div class="table-responsive">
		<table id="datatable" style="width: 100%;" class="table">
		<thead>
			<tr>
			<th>Title</th>
			<th>Author</th>
			<th>Publish Date</th>
			<th>#</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($model->getList() as $item) : ?>
			<tr>
			<td><?= $item->title; ?></td>
			<td><?= $item->author; ?></td>
			<td><?= $item->publishDate; ?></td>
			<td><a href="index.php?task=blog.removeItem&id=<?= $item->id; ?>"><i class="fa fa-trash-o"></i></a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		</table>
    </div>
</section>
