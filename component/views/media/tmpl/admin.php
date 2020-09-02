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
                <form id="upload" action="<?= config::$site; ?>index.php?task=media.upload&mode=raw" class="dropzone" enctype="multipart/form-data">
                    <div class="fallback">
                      <input name="file" type="file" multiple />
                    </div>
                    <input type="hidden" name="dir" value="<?= $dir; ?>" />
                    <div class="dz-message">
                      <p>Arrossega arxius per pujar-los en aquest directori.</p>
                    </div>
                    <div class="mb-3">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="resize" id="resize1" value="1">
                        <label class="form-check-label" for="resize1">Resize (only images)</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="resize" id="resize2" value="0" checked>
                        <label class="form-check-label" for="resize2">Not resize</label>
                      </div>
                    </div> 
                    <div class="mb-3">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="watermark" id="watermark1" value="1">
                        <label class="form-check-label" for="watermark1">Watermark (only in resize mode)</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="watermark" id="watermark2" value="0" checked>
                        <label class="form-check-label" for="watermark2">Not watermark</label>
                      </div>
                    </div>
                    <div class="mb-3">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="rename" id="rename1" value="1" checked>
                        <label class="form-check-label" for="rename1">Rename file</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="rename" id="rename2" value="0">
                        <label class="form-check-label" for="rename2">Not rename</label>
                      </div>
                    </div>
                </form>   
            </div>
            </div>
        </div>
    </div>

  </div>
</section>
