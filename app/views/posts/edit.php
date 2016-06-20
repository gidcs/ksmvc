<?php
	$this->includes("layouts/header",$data);
	$this->includes("layouts/alerts",$data);
?>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Posts</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="/posts/<?=$data['id']?>/edit">
						<input id="_method" type="hidden" class="form-control" name="_method" value="PUT">
						<?php if(!isset($data['error'])) { ?>
                        <div class="form-group">
							<?php if(isset($data['title'])){ ?>
							<input id="title" type="text" class="form-control" name="title" value="<?=$data['title']?>">
							<?php } else { ?>
							<input id="title" type="text" class="form-control" name="title" placeholder="Enter title here" value="">
							<?php } ?>		
                        </div>
						<div class="form-group">
							<?php if(isset($data['content'])){ ?>
							<textarea class="form-control" rows="12" id="content" name="content"><?=$data['content']?></textarea>
							<?php } else { ?>
							<textarea class="form-control" rows="12" id="content" name="content"></textarea>
							<?php } ?>
                        </div>
						<?php } ?>

                        <div class="form-group text-center">
							<a href="/posts/<?=$data['id']?>" class="btn btn-default">&nbsp;Back&nbsp;</a>
							<?php if(!isset($data['error'])) { ?>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-btn fa-post"></i> Update
							</button>
							<?php } ?>
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