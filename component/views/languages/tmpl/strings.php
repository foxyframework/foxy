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

$model 	= application::getModel('languages');
$file   = application::getVar('file', 'en-gb');
?>

<section class="container-fluid my-5">
    <div class="row">
        <form method="post" action="<?= config::$site; ?>index.php?task=languages.saveFile">
            <textarea style="width:100%;height:500px;" name="strings">
            <?php 
            $i = 0;
            foreach($model->getStrings($file) as $k => $v) : ?>
                <?= $k; ?>="<?= $v; ?>"
            <?php 
            $i++;
            endforeach; ?>
            </textarea>
            <div class="form-group">
                <input type="submit" value="Guardar" class="btn btn-primary">
            </div>
        </form>
    </div>
</section>