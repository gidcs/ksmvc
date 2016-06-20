<?php
	$this->includes("layouts/header",$data);
	$this->includes("layouts/alerts",$data);
?>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">New Posts
				</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="/posts/new">
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

                        <div class="form-group text-center">			
							<a href="/" class="btn btn-default">&nbsp;Back&nbsp;</a>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-btn fa-post"></i> Publish
							</button>
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