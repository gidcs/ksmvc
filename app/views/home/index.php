<?php
	$this->includes("layouts/header",$data)
?>

<div class="container">
	<div class="row">
        <div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Dashboard</div>
				<div class="panel-body">
					<?php if(!isset($data['username'])){ ?>
					Your Application's Landing Page.
					<?php } else { ?>
					You are logged in!
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	$this->includes("layouts/footer")
?>