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

if(!$user->getAuth()) {
    $app->redirect($config->site);
}
?>

<script>
function generateUUID()
{
	var d = new Date().getTime();
	
	if( window.performance && typeof window.performance.now === "function" )
	{
		d += performance.now();
	}
	
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c)
	{
		var r = (d + Math.random()*16)%16 | 0;
		d = Math.floor(d/16);
		return (c=='x' ? r : (r&0x3|0x8)).toString(16);
	});

return uuid;
}

$(document).ready(function() {
  $( '#keygen' ).on('click',function(e) {
      e.preventDefault();
      $( '#apikey' ).val( generateUUID() );
  });
});
</script>

<section class="forms">
  <div class="container-fluid">
    <!-- Page Header-->
    <header>
      <h1 class="h3 display">Profile</h1>
    </header>
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-profile">
          <div style="background-image: url('template/nova/img/photos/paul-morris-116514-unsplash.jpg');" class="card-header"></div>
          <div class="card-body text-center"><img src="https://secure.gravatar.com/avatar/<?= md5($user->email); ?>?size=100" class="card-profile-img">
            <h3 class="mb-3"><?= $user->username; ?></h3>
            <p>Image from <a href="https://secure.gravatar.com" target="_blank">Gravatar.com</a></p>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <form class="card" method="post" action="index.php?view=profile&task=saveProfile" autocomplete="off">
          <div class="card-body">
            <h3 class="card-title">Edit Profile</h3>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group mb-4">
                  <label class="form-label">Cargo</label>
                  <input type="text" placeholder="Cargo" name="cargo" value="<?= $user->cargo; ?>" class="form-control">
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="form-group mb-4">
                  <label class="form-label">Username</label>
                  <input type="text" placeholder="Username" value="<?= $user->username; ?>" class="form-control" readonly="true">
                </div>
              </div>
              <div class="col-sm-6 col-md-4">
                <div class="form-group mb-4">
                  <label class="form-label">Email address</label>
                  <input type="email" placeholder="Email" value="<?= $user->email; ?>" name="email" class="form-control">
                </div>
              </div>
              <div class="col-md-6 col-md-3">
                <div class="form-group mb-4">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control">
                </div>
              </div>
              <div class="col-md-6 col-md-4">
                <div class="form-group mb-4">
                  <label class="form-label">Address</label>
                  <input type="text" placeholder="Home Address" value="<?= $user->address; ?>" name="address" class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group mb-0">
                  <label class="form-label">About Me</label>
                  <textarea rows="5" name="bio" placeholder="Here can be your description" value="Mike" class="form-control"><?= $user->bio; ?></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <div class="input-group my-3">
                  <input type="text" placeholder="APIKey" id="apikey" value="<?= $user->apikey; ?>" name="apikey" class="form-control">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="keygen">Generar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <button data-target="#myModal" class="btn btn-danger pr-2" data-toggle="modal" data-original-title="<?php echo $lang->get('CW_SETTINGS_DELETE_ACCOUNT'); ?>"><?php echo $lang->get('CW_SETTINGS_DELETE_ACCOUNT'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- modal delete -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $lang->get('CW_SETTINGS_DELETE_ACCOUNT_TITLE'); ?></h4>
        </div>
        <div class="modal-body">
            <?php echo $lang->replace('CW_SETTINGS_DELETE_ACCOUNT_BODY', $config->sitename, $config->sitename); ?>
            <input type="text" name="proceed" id="proceed" />
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal"><?php echo $lang->get('CW_CANCEL'); ?></button>
            <button onclick="deleteAccount(<?php echo $user->name; ?>,<?php echo $config->site; ?>)';" class="btn btn-danger" data-dismiss="modal"><?php echo $lang->get('CW_DELETE'); ?></button>
        </div>
    </div>
  </div>
</div>
<!-- end modal delete -->
