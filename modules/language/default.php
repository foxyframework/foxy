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
$languages = languageHelper::getLanguages();
?>

<?php if(count($languages) > 1) : ?>
<div id="lang" class="d-flex">

    <?php foreach($languages as $lang) : ?>

        <div class="pr-2"><a href="index.php?task=register.setCookie&lang=<?= $lang->code; ?>&mode=raw&return=<?= base64_encode($view.'.html'); ?>" class="lang"><img data-lang="<?= $lang->code; ?>" src="assets/img/flags/<?= $lang->code; ?>.gif" alt="<?= $lang->title; ?>"></a></div>

    <?php endforeach; ?>

</div>
<?php endif; ?>