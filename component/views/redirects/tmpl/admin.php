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
$model = application::getModel('redirects');
?>

<section class="container-fluid my-5">
	<?php
		$fields  = ['id', 'old_url', 'new_url', 'status'];
		$columns = ['Id', 'Old url', 'New url', 'Status'];
		$buttons = ['delete', 'edit', 'order', 'status'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns, $buttons); ?>
</section>