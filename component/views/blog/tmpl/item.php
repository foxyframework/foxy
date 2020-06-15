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

$id    = $app->getVar('id', 0, 'get');
if($id == 0) {
	$app->redirect('error.php');
}

$model = $app->getModel();
$item = $model->getItem();
?>

<!-- Main Content -->
<section class="container">
      <div class="row my-5">
        <div class="col-md-12">
			<div class="blog-post">

          		<h2 class="blog-post-title"><?= $item->title; ?></h2>
              	<sppan class="blog-post-meta">Creat per <a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a> el <?= date('j F Y', strtotime($item->publishDate)); ?> · <?= $item->hits; ?> cops Arxivat a <?= $model->renderTags($item->tags); ?></p>

				<!-- Post Content -->
				<article><?= $item->fulltext; ?></article>

			</div>
        </div>
      </div>
</section>
