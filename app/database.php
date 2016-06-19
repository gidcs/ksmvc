<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$env_mode = $db['default_database'];
$capsule = new Capsule();
$capsule->addConnection([
	'driver' => $db[$env_mode]['adapter'],
	'host' => $db[$env_mode]['host'],
	'port' => $db[$env_mode]['port'],
	'username' => $db[$env_mode]['user'],
	'password' => $db[$env_mode]['pass'],	
	'database' => $db[$env_mode]['name'],	
	'charset' => $db[$env_mode]['charset'],	
	'collation' => $db[$env_mode]['collation'],
	'prefix' => $db[$env_mode]['table_prefix']
]);
$capsule->bootEloquent();
