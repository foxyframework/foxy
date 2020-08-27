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
$id = application::getVar('id', 0);
?>

<section class="forms">
  	<div class="container-fluid">

    	<div class="row my-4">
      		<div class="col-lg-12">
                <form name="blogForm" id="blogForm" method="post" action="index.php?task=blocks.saveBlockItem">			
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <?= html::renderBlockForm($id); ?>
                </form>

            </div> 
        </div>

    </div>
</section>
            