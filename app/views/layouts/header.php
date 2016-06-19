<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>KSMVC</title>
	
	<!-- Bootstrap core CSS -->
	<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
	 <!-- Custom styles for this template -->
	<link type="text/css" rel="stylesheet" href="css/main.css">
	
</head>
<body>
<!-- Static navbar -->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">KSMVC</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
		
			<!-- Left Side Of Navbar -->
			<ul class="nav navbar-nav">
				<li><a href="/">Home</a></li>
			</ul>
			
			<!-- Right Side Of Navbar -->
			<ul class="nav navbar-nav navbar-right">
			<?php if(!isset($data['login_username'])){ ?>
				<li><a href="/login">Login</a></li>
				<li><a href="/register">Register</a></li>
			<?php } else { ?>
				<!-- Authentication Links -->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<?=$data['login_username']?> <span class="caret"></span>
					</a>

					<ul class="dropdown-menu" role="menu">
						<li><a href="/logout"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
					</ul>
				</li>
			<?php } ?>
			</ul>
			
		</div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</nav>
