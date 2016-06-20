<?php
	$this->includes("layouts/header",$data)
?>

<?php if(isset($data['error'])){ ?>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Error: </strong> <?=$data['error']?>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/register">
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
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
								<?php if(isset($data['email'])){ ?>
                                <input id="email" type="email" class="form-control" name="email" value="<?=$data['email']?>">
								<?php } else { ?>
								<input id="email" type="email" class="form-control" name="email" value="">
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
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
							</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Register
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