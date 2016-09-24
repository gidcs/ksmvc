<?php
  $this->includes("layouts/header",$data);
  $this->includes("layouts/alerts",$data);
?>

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title">
            Reset Password
          </div>
        </div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" 
          action="<?=Route::URI('PasswordResetController@postPasswordResetActual')?><?=$data['email']?>/<?=$data['token']?>">
            <div class="form-group">
              <label for="password" class="col-md-4 control-label">Password</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password">
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
              <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-btn fa-user"></i> Change Password
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  $this->includes("layouts/footer")
?>
