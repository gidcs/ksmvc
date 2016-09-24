<?php
  $this->includes("layouts/header",$data);
  $this->includes("layouts/alerts",$data);
?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Login</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="/login">
            <div class="form-group">
              <label for="username" class="col-md-4 control-label">Username</label>
              <div class="col-md-6">
                <?php if(isset($data['username'])){ ?>
                <input id="username" type="text" class="form-control" name="username" value="<?=$data['username']?>">
                <?php } else { ?>
                <input id="username" type="text" class="form-control" name="username" value="">
                <?php } ?>
              </div>
            </div>
            
            <div class="form-group">
              <label for="password" class="col-md-4 control-label">Password</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password">
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="remember"> Remember Me
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-btn fa-sign-in"></i> Login
                </button>

                <a class="btn btn-link" href="password_reset">Forgot Your Password?</a>
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