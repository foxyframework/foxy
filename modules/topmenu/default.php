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
include_once('helper.php');

$app    = factory::get('application');
$config = factory::get('config');
$url    = factory::get('url');
$user   = factory::get('user');
$lang   = factory::get('language');
?>

<header>
    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-7 py-4">
            <h4 class="text-white">Menu</h4>
            <ul class="list-unstyled">
              <?php foreach(topmenuHelper::getItems() as $item) : ?>
              <?php $item->translation != '' ? $title = $lang->get($item->translation) : $title = $item->title; ?>
              <?php if($item->auth == 0) : ?>
              <li><a <?php if($item->type == 1): ?>data-toggle="modal" data-target="#modal<?= $item->id; ?>"<?php endif; ?> class="text-light" href="<?= $url->genUrl($item->url); ?>"><?= $title; ?></a></li>
              <?php endif; ?>
              <?php if($item->auth == 1 && !$user->getAuth()) : ?>
              <li><a <?php if($item->type == 1): ?>data-toggle="modal" data-target="#modal<?= $item->id; ?>"<?php endif; ?> class="text-light" href="<?= $url->genUrl($item->url); ?>"><?= $title; ?></a></li>
              <?php endif; ?>
              <?php if($item->auth == 2 && $user->getAuth()) : ?>
              <li><a <?php if($item->type == 1): ?>data-toggle="modal" data-target="#modal<?= $item->id; ?>"<?php endif; ?> class="text-light" href="<?= $url->genUrl($item->url); ?>"><?= $title; ?></a></li>
              <?php endif; ?>
              <?php endforeach; ?>
              <?php if($user->getAuth() && $user->level == 1) : ?>
              <li><a class="text-light" href="<?= $url->genUrl('index.php?view=admin'); ?>">Administrator</a></li>
              <?php endif; ?>
            </ul>
          </div>
          <div class="col-sm-4 offset-md-1 py-4">
            <h4 class="text-white">Contacto</h4>
            <ul class="list-unstyled">
              <li><a href="https://twitter.com/Artic___Monkey" class="text-white" target="_blank"><i class="fa fa-twitter"></i> Follow on Twitter</a></li>
              <li><a href="#" class="text-white"><i class="fa fa-envelope"></i> Email me</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container d-flex justify-content-between">
        <a href="<?= $config->site; ?>" class="navbar-brand d-flex align-items-center">
          <strong><?= $config->sitename; ?></strong>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>

    <?php foreach(topmenuHelper::getMenuModalItems() as $item) : ?>
      <!-- Modal <?= $item->title; ?> -->
    <div class="modal fade" id="modal<?= $item->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?= $item->id; ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel<?= $item->id; ?>"><?= $item->title; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?= $app->getModule(''.$item->module.''); ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </header>