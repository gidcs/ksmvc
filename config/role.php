<?php

Role::add([
	'Visitor',
	'User',
	'Manager',
	'Admin',
]);


Role::find('Visitor')->set_permissions([
	'Auth',
	'HomeController'
]);

Role::find('User')->set_permissions([
	'Auth',
	'HomeController'
]);

Role::find('Admin')->set_permissions([
	'Auth',
	'HomeController',
	'UsersController'
]);
