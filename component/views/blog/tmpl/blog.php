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

$model = $app->getModel();
$tag   = $app->getVar('tag', '', 'get');
$page  = $app->getVar('page', 1, 'get');
$items = $model->getList();
?>

<style>
.pagination { 
	width: 100%;
	text-align: center;
}
li.disabled a {
   pointer-events: none;
   cursor: default;
}
li.prev, li.next {
 	margin: 0 10px;
}
</style>

<!-- Main Content -->
<section class="container">
    <div class="row my-5">

        <div class="col-md-12">

			<?php if(count($items)) : ?>
			<?php foreach($items as $item) : ?>
			<div class="blog-post">
				<a href="index.php?view=blog&layout=item&id=<?= $item->id; ?>">
					<h2 class="blog-post-title"><?= $item->title; ?></h2>
				</a>
				<p class="blog-post-meta">Creat per
					<a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a>
					el <?= date('j F Y', strtotime($item->publishDate)); ?>  Vist <?= $item->hits; ?> cops
			    </p>
							
				<<?= $model->trimText($item->fulltext, 150); ?>
				<hr>						
						
			<?php endforeach; ?>
						
			<?php else : ?>
			No hi han entrades al blog.
			<?php endif; ?>

			</div>
		</div>
    </div>
</section>
