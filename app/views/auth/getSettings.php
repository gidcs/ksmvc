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
<?php } else if(isset($data['success'])){ ?>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success: </strong> <?=$data['success']?>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/settings">
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email</label>
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
                                    <i class="fa fa-btn fa-user"></i> Update
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