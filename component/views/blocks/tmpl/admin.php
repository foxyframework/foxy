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
$form = FOXY_COMPONENT.DS.'forms'.DS.'blocks.xml';
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-6">
        		<div class="card">
          			<div class="card-header d-flex align-items-center">
						<h4>Blocks</h4>
          			</div>
          			<div class="card-body">
						<form name="blockForm" id="blockForm" method="post" action="index.php?task=blocks.saveBlock">			
                            <?= html::getFoldersField($form, 'title', FOXY_BASE.DS.'blocks'); ?>
                            <?= html::getPagesField($form, 'pageId'); ?>
                            <?= html::getListField($form, 'language'); ?>
							<div class="form-group">
								<input type="submit" value="<?= language::get('FOXY_SAVE'); ?>" class="btn btn-primary">
							</div>
						</form>
          			</div>
        		</div>
      		</div> 
			<div class="col-lg-6">
       	 		
      		</div>      	
        </div>
	</div>
</section>

<section class="container-fluid my-5">
	<?php
		$fields = ['id', 'title', 'pageId', 'language'];
		$columns = ['Id', 'Title', 'Page', 'Language'];
	?>
	<form name="tableForm" id="tableForm" action="" method="post">
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns); ?>
	</form>
</section>
