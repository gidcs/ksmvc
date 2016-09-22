<?php
//composer autoloader

require_once('../vendor/autoload.php');
require_once('../config/config.php');
require_once('../core/ErrorMessage.php');
require_once('../core/Controller.php');
require_once('../core/Route.php');
require_once('../core/Router.php');
require_once('../core/Role.php');
require_once('../core/Helper.php');
require_once('../core/Token.php');
require_once('../core/Mail.php');
require_once('../core/App.php');

//bootup databse
require_once('database.php');
//set routing for Route
require_once('router.php');
//set role and allowed Method in Role
require_once('role.php');
//set jwt_key for Token
require_once('token.php');
//set smtp information for Mail
require_once('mail.php');
