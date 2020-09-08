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

?>

<div id="<?= $_POST['arg1']; ?>" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#<?= $_POST['arg1']; ?>" data-slide-to="0" class="active"></li>
      <li data-target="#<?= $_POST['arg1']; ?>" data-slide-to="1"></li>
      <li data-target="#<?= $_POST['arg1']; ?>" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="<?= $_POST['arg2']; ?>" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="<?= $_POST['arg3']; ?>" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="<?= $_POST['arg4']; ?>" class="d-block w-100" alt="...">
      </div>
    </div>
    <a class="carousel-control-prev" href="#<?= $_POST['arg1']; ?>" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#<?= $_POST['arg1']; ?>" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>