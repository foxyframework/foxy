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

$id    = application::getVar('id', 0, 'get');
if($id == 0) {
	application::redirect('error.php');
}

$model = application::getModel();
$item = $model->getItemById();
$params = $model->getParams('blog');
?>

<!-- Main Content -->
<section class="container<?php if($params->fluid == 1) : ?>-fluid<?php endif; ?>">
      <div class="row my-5">
        <div class="col-md-12">
			<div class="blog-post">

          		<h2 class="blog-post-title"><?= $item->title; ?></h2>
              	<sppan class="blog-post-meta"><?= language::get('FOXY_BLOG_CREATED_BY'); ?> <a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a> &dot; <?= date('j F Y', strtotime($item->publishDate)); ?> &dot; <?= language::get('FOXY_BLOG_HITS'); ?>  <?= $item->hits; ?> &dot; <?= language::get('FOXY_BLOG_TAGS'); ?> <?= $model->renderTags($item->tags); ?></p>

				<!-- Post Content -->
				<?= $item->fulltext; ?>

			</div>
        </div>
      </div>
</section>
