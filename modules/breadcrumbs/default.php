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
include_once('helper.php');

$url    = factory::get('url');
$lang   = factory::get('language');
$app    = factory::get('application');
$view   = $app->getVar('view', '');
$layout = $app->getVar('layout', '');
?>

<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $url->genUrl('index.php?view=home'); ?>">Home</a></li>
            <li class="breadcrumb-item <?php if($layout == '') : ?>active<?php endif; ?>">
            <?php if($layout != '') : ?><a href="<?= $url->genUrl('index.php?view='.$view); ?>"><?php endif; ?>
            <?= ucfirst($view); ?>
            <?php if($layout != '') : ?></a><?php endif; ?>
            </li>
            <?php if($layout != '') : ?>
            <li class="breadcrumb-item active"><?= ucfirst($layout); ?></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
