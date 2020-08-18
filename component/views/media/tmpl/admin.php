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

$model = application::getModel('media');
$dir   = application::getVar('folder', '');
?>

<section class="forms">
  <div class="container-fluid">

    <div class="row my-4">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h4>Media Manager</h4>
          </div>
          <div class="card-body">
            <p>Com administrador pots administrar arxius multimedia.</p>
                <div class="d-flex flex-row">
                <?php foreach($model->getMediaFolders($dir) as $folder) : ?>
                    <?= '<a href="index.php?view=media&layout=admin&folder='.$folder.'" class="p-2"><i class="fa fa-folder fa-4x"></i><br>'.$folder.'</a>'; ?>
                <?php endforeach; ?>
                </div>
                <div class="d-flex flex-row" id="media">
                <?php foreach($model->getMediaFiles($dir) as $file) : ?>
                    <?php $dir == '' ? $link = 'assets/img/'.$file : $link = 'assets/img/'.$dir.'/'.$file; ?>
                    <img src="<?= $link; ?>" class="card-img-top" alt="...">
                <?php endforeach; ?>
                </div>
          </div>
        </div>
      </div>
    <div class="col-12 col-md-6">
            <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4>Upload</h4>
            </div>
            <div class="card-body">
                <p>Com administrador pots administrar arxius multimedia.</p>
                    
            </div>
            </div>
        </div>
    </div>

  </div>
</section>
