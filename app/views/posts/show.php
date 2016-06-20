<?php
	$this->includes("layouts/header",$data);
	$this->includes("layouts/alerts",$data);
?>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><?=$data['title']?></div>
                <div class="panel-body">
                    <p><?=$data['content']?></p>
                </div>
				<div class="panel-footer">
					By <?=$data['owner']?> on <?=$data['date']?>
				</div>
            </div>
			<div class="row text-center">
				<form role="form" method="POST" action="/posts/<?=$data['id']?>">
					<div class="form-group">
						<a href="/" class="btn btn-default">&nbsp;Back&nbsp;</a>
						<?php if($data['has_permission']==1){ ?>
						<a href="/posts/<?=$data['id']?>/edit" class="btn btn-primary">&nbsp;Edit&nbsp;</a>
						<input id="_method" type="hidden" class="form-control" name="_method" value="DELETE">
						<button type="submit" class="btn btn-danger">Delete</button>
						<?php } ?>
					</div>
				</form>
			</div>
        </div>
    </div>
</div>

<?php
	$this->includes("layouts/footer")
?>