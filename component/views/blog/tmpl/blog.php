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

$model = application::getModel();
$tag   = application::getVar('tag', '', 'get');
$page  = application::getVar('page', 1, 'get');
$items = $model->getList();
?>

<!-- Main Content -->
<section class="container<?php if($params->fluid == 1) : ?>-fluid<?php endif; ?>">
    <div class="row my-5">

        <div class="col-md-12">

			<?php if(count($items)) : ?>
			<?php foreach($items as $item) : ?>
			<?php $params = json_decode($item->params); ?>
			<div class="blog-post">
				<a href="<?= url::genUrl('index.php?view=blog&layout=item&id='.$item->id.'&slug='.$item->alias); ?>">
					<h2 class="blog-post-title"><?= $item->title; ?></h2>
				</a>
				<p class="blog-post-meta">
					<?php if($params->show_author == 1) : ?>
					<?= language::get('FOXY_BLOG_CREATED_BY'); ?>
					<a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a>&dot; 
					<?php endif; ?>
					<?php if($params->show_date == 1) : ?>
					<?= date('j F Y', strtotime($item->publishDate)); ?> &dot;  
					<?php endif; ?>
					<?php if($params->show_hits == 1) : ?>
					<?= language::get('FOXY_BLOG_HITS'); ?> <?= $item->hits; ?> &dot; 
					<?php endif; ?>
					<?php if($params->show_tags == 1) : ?>
					<?= language::get('FOXY_BLOG_TAGS'); ?> <?= $model->renderTags($item->tags); ?>
					<?php endif; ?>

			    </p>

				<?php application::trigger('onBeforeBlogPost', $item); ?>
							
				<?= $model->trimText($item->fulltext, 500); ?>
				<?php if($params->show_readmore == 1) : ?><p><a href="<?= url::genUrl('index.php?view=blog&layout=item&id='.$item->id.'&slug='.$item->alias); ?>"><?= language::get('FOXY_BLOG_READMORE'); ?></a></p><?php endif; ?>
				
				<?php application::trigger('onAfterBlogPost', $item); ?>

				<hr>						
						
			<?php endforeach; ?>
						
			<?php else : ?>
			<?= $lang->get('FOXY_BLOG_NO_ENTRIES'); ?>
			<?php endif; ?>

			</div>
		</div>

		<?= $model->pagination($_GET); ?>

    </div>
</section>
