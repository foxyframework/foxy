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

<section class="container-fluid my-5">
	<?php
		$fields = ['title', 'author', 'publishDate'];
		$columns = ['Title', 'Author', 'Publish Date'];
		$buttons = ['delete', 'edit', 'order', 'status', 'params'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns, $buttons); ?>
</section>
