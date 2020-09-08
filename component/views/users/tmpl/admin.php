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

?>

<section class="container-fluid my-5">
	<?php
		$fields = ['id', 'username', 'email', 'level', 'lastvisitDate'];
		$columns = ['Id', 'Name', 'Email', 'Usergroup', 'Last Visit'];
		$buttons = ['delete', 'edit', 'order', 'status'];
	?>
	<?= html::renderTable('datatable', 'id', $model->getList(), $fields, $columns, $buttons); ?>
</section>
