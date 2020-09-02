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
		$fields = ['id', 'title', 'pageId', 'language'];
		$columns = ['Id', 'Title', 'Page', 'Language'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns); ?>
</section>
