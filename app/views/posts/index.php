<?php
	$this->includes("layouts/header",$data)
?>

<div class="container">
    <div class="row">
	    <div class="col-md-10 col-md-offset-1">
			<?php foreach($data['posts'] as $post){ ?>
			<a href="/posts/<?=$post['id']?>"><h2><?=$post['title']?></h2></a>
			<p>By <?=$post['owner']?><span class="pull-right">on <?=$post['date']?></span></p>
			<hr class="divider">
			<?php } ?>
			<?php if($data['max_id']!=1){ ?>
			<div class="row text-center">
				<div class="btn-group">
					<a href="/" class="btn btn-default <?php if($data['page_id']==1) echo "disabled";?>">
						<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
					</a>
					<a href="/page/<?=$data['page_id']-1?>" class="btn btn-default">
						<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
					</a>
					<a href="/page/<?=$data['page_id']+1?>" class="btn btn-default">
						<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
					</a>
					<a href="/page/<?=$data['max_id']?> " class="btn btn-default <?php if($data['page_id']==$data['max_id']) echo "disabled";?>">
						<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
					</a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php
	$this->includes("layouts/footer")
?>