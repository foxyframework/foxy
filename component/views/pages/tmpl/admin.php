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

$model 	= application::getModel('pages');
?>

<section class="container-fluid my-5">
	<?php
		$fields = ['id', 'title', 'translaton', 'url', 'auth', 'type', 'module', 'inMenu' => array('field' => 'inMenu', 'format' => 'bool')];
		$columns = ['Id', 'Title', 'Translation', 'Url', 'Auth', 'Type', 'Module', 'Menu'];
		$buttons = ['delete', 'edit', 'order', 'status', 'params'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns, $buttons); ?>
</section>