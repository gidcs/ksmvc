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