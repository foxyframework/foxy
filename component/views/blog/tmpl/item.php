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
<div class="container">
      <div class="row">
        <div class="col-md-10 mx-auto">
          	<div class="wrap blog-item">

          		<p class="post-heading">
              		<h1>#<?= $item->title; ?></h1>
              		<span class="meta">Creat per <a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a> el <?= date('j F Y', strtotime($item->publishDate)); ?> · <?= $item->hits; ?> cops Arxivat a <?= $model->renderTags($item->tags); ?></span>
            	</p>

				<!-- Post Content -->
				<article><?= $item->fulltext; ?></article>

			</div>
        </div>
      </div>
</div>
