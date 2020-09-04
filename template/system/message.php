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

?>

<?php if(isset($_SESSION['message'])) : ?>
<div class="toast toast-<?= $_SESSION['messageType']; ?>" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <img src="<?= config::$site; ?>assets/img/icons/icon16.png" class="rounded mr-2" alt="...">
    <strong class="mr-auto"><?= ucfirst($_SESSION['messageType']); ?></strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="toast-body">
  <?= $_SESSION['message']; ?> 
  </div>
</div>
<?php endif;
unset($_SESSION['message'], $_SESSION['messageType']);
?>