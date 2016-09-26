<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?=App::info('description')?>"> 
  <title><?=App::info('title')?> - <?=App::info('subtitle')?></title>
  
  <!-- Bootstrap core CSS -->
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
   <!-- Custom styles for this template -->
  <link type="text/css" rel="stylesheet" href="/css/main.css">
  
</head>
<body id="app-layout">
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
    
      <!-- Collapsed Hamburger -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    
      <!-- Branding Image -->
      <a class="navbar-brand" href="#"><?=App::info('title')?></a>
    </div>
    
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
    
      <!-- Left Side Of Navbar -->
      <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
        <?php if((isset($data['login_user'])) && Role::is_role('Admin')){ ?>
          <!-- Management Links -->
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              Manage <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li><a href="/manage/settings">Settings</a></li>
              <li><a href="<?=Route::URI('UsersController#index');?>"></i>Users</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
      
      <!-- Right Side Of Navbar -->
      <ul class="nav navbar-nav navbar-right">
      <?php if(!isset($data['login_user'])){ ?>
        <li><a href="<?=Route::URI('AuthController@getLogin');?>">Login</a></li>
        <li><a href="<?=Route::URI('AuthController@getRegister');?>">Register</a></li>
      <?php } else { ?>
        <!-- Authentication Links -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <?=$data['login_user']->username?> <span class="caret"></span>
          </a>

          <ul class="dropdown-menu" role="menu">
            <li><a href="<?=Route::URI('AuthController@getSettings');?>">Profile</a></li>
            <li><a href="<?=Route::URI('AuthController@getLogout');?>">Logout</a></li>
          </ul>
        </li>
      <?php } ?>
      </ul>
      
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>
