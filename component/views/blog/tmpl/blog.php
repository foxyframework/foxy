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
$params = $model->getParams('blog');
?>

<!-- Main Content -->
<section class="container<?php if($params->fluid == 1) : ?>-fluid<?php endif; ?>">
    <div class="row my-5">

        <div class="col-md-12">

			<?php if(count($items)) : ?>
			<?php foreach($items as $item) : ?>
			<div class="blog-post">
				<a href="<?= $url->genUrl('index.php?view=blog&layout=item&id='.$item->id.'&slug='.$item->alias); ?>">
					<h2 class="blog-post-title"><?= $item->title; ?></h2>
				</a>
				<p class="blog-post-meta"><?= $lang->get('FOXY_BLOG_CREATED_BY'); ?>
					<a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a>
					&dot; <?= date('j F Y', strtotime($item->publishDate)); ?> &dot;  <?= $lang->get('FOXY_BLOG_HITS'); ?> <?= $item->hits; ?> &dot; <?= $lang->get('FOXY_BLOG_TAGS'); ?> <?= $model->renderTags($item->tags); ?></p>
			    </p>
							
				<?= $model->trimText($item->fulltext, 500); ?>
				<hr>						
						
			<?php endforeach; ?>
						
			<?php else : ?>
			<?= $lang->get('FOXY_BLOG_NO_ENTRIES'); ?>
			<?php endif; ?>

			</div>
		</div>
    </div>
</section>
