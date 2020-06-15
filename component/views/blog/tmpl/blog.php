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

<!-- Main Content -->
<section class="container<?php if($model->getParams()->fluid == 1) : ?>-container<?php endif; ?>">
    <div class="row my-5">

        <div class="col-md-12">

			<?php if(count($items)) : ?>
			<?php foreach($items as $item) : ?>
			<div class="blog-post">
				<a href="index.php?view=blog&layout=item&id=<?= $item->id; ?>">
					<h2 class="blog-post-title"><?= $item->title; ?></h2>
				</a>
				<p class="blog-post-meta"><?= $lang->get('FOXY_BLOG_CREATED BY'); ?>
					<a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a>
					&dot; <?= date('j F Y', strtotime($item->publishDate)); ?>  <?= $lang->get('FOXY_BLOG_HITS'); ?> <?= $item->hits; ?>
			    </p>
							
				<?= $model->trimText($item->fulltext, 500); ?>
				<hr>						
						
			<?php endforeach; ?>
						
			<?php else : ?>
			No hi han entrades al blog.
			<?php endif; ?>

			</div>
		</div>
    </div>
</section>
