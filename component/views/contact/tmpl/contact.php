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

<section class="forms">
  	<div class="container">

    	<div class="row my-4">
      		<div class="col-lg-12">
        		
				<h2>Contact</h2>
          		
                <form name="blogForm" id="blogForm" method="post" action="index.php?task=contact.sendForm">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <?=  $html->getTextField('contact', 'name'); ?>
                    <?=  $html->getTextField('contact', 'phone'); ?>
                    <?=  $html->getEmailField('contact', 'email'); ?>	
                    <?=  $html->getTextareaField('contact', 'message'); ?>	
                    <div class="form-group">
                        <input type="submit" value="<?= $lang->get('FOXY_SEND'); ?>" class="btn btn-primary">
                    </div>
                </form>
    </div>
</section>