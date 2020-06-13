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

?>

<?php if(isset($_SESSION['message'])) : ?>
<div id="foxymessage">
  <div class="alert alert-<?= $_SESSION['messageType']; ?> alert-dismissible fade show" role="alert">
    <strong><?= $_SESSION['message']; ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>
<?php endif;
unset($_SESSION['message'], $_SESSION['messageType']);
?>
