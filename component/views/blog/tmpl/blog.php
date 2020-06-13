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
    <div class="container">
      <div class="row">

        <div class="col-md-12">
          	<div class="wrap">
    
				<div class="main">

						<?php if(count($items)) : ?>
						<?php foreach($items as $item) : ?>
						<div class="post-preview">
							<a href="index.php?view=blog&layout=item&id=<?= $item->id; ?>">
							  <h2>
								#<?= $item->title; ?>
							  </h2>
							  <h3 class="post-subtitle">
								<?= $model->trimText($item->fulltext, 150); ?>
							  </h3>
							</a>
							<p class="post-meta">Creat per
							  <a href="<?= $item->author_link; ?>" target="_blank"><?= $item->author; ?></a>
							  el <?= date('j F Y', strtotime($item->publishDate)); ?>  Vist <?= $item->hits; ?> cops</p>
						</div>
						<hr>						
						
						<?php endforeach; ?>
						
						<?php else : ?>
						No hi han entrades al blog.
						<?php endif; ?>

				 </div>
			</div>
        </div>

      </div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="fairModal" tabindex="-1" role="dialog" aria-labelledby="fairModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <h5 class="modal-title" id="fairModalLabel">Ajuda'ns</h5>
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		    </button>
		  </div>
		  <div class="modal-body">
		  	<div class="row">
			  	<div class="col-md-2">
			  		<img src="media/faircoin.png" alt="Faircoin" class="img-fluid">
			  	</div>
			  	<div class="col-md-10">
					Acceptem donacions amb Faircoin la cryptomoneda ètica.	    				
				</div>
		    </div>
		    <p></p>
		    <div class="row">
				<div class="col-md-12">
					<a href="faircoin:fQykiFJVktuDYKcYkGVhM9yt1YcZ5kdVg6?label=Donacions per a mantenir surtdelcercle.cat&message=Tota%20ajuda%20sera%20benvinguda%21">fcXgoJhkUYxKuHqQpC7vUK3LMMZpE5pu1x</a>
					<p></p>
					<divclass="text-center"><img src="media/canva.png" alt="Donacio Faircoin"></div>
				</div>
		    </div>		    
		  </div>
		</div>
	  </div>
	</div>
