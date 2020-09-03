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
include_once(dirname(__FILE__).DS.'helper.php');

?>

<div id="lang" class="d-flex">

    <?php foreach(languageHelper::getLanguages() as $lang) : ?>

        <div class="pr-2"><a href="#" class="lang"><img data-lang="<?= $lang->code; ?>" src="assets/img/flags/<?= $lang->code; ?>.gif" alt="<?= $lang->title; ?>"></a></div>

    <?php endforeach; ?>

</div>