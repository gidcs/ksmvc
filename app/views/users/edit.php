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
            Edit User
          </div>
        </div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" 
            action="<?=Route::URI('UsersController#update')?><?=$data['user']->id?>/edit">
            <?=Route::Method('PUT')?>
            <div class="form-group"> 
              <label for="username" class="col-md-4 control-label">Username</label>
              <div class="col-md-6">
                <input id="username" type="text" class="form-control" name="username" 
                  value="<?=$data['user']->username?>">
              </div>
            </div>
            <div class="form-group"> 
              <label for="email" class="col-md-4 control-label">Email</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" 
                  value="<?=$data['user']->email?>">
              </div>
            </div>
            <div class="form-group"> 
              <label for="role" class="col-md-4 control-label">Role</label>
              <div class="col-md-6">
                <select id="role" type="text" class="form-control" name="role">
                <?php foreach(Role::All() as $k=>$v) { ?>
                  <option value=<?=$k?>
                    <?=($k==$data['user']->role)?' selected':''?>
                  >
                    <?=$v->_name?>
                  </option>
                <?php } ?>
                </select>
              </div>
            </div>
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
                  <i class="fa fa-btn fa-user"></i> Update
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row text-center">
        <a href="<?=Route::URI('UsersController#index')?>" class="btn btn-default">&nbsp;Back&nbsp;</a>
      </div>
    </div>
  </div>
</div>

<?php
  $this->includes("layouts/footer")
?>
