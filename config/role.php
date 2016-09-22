<?php

Role::add([
	'Visitor',
	'User',
	'Manager',
	'Admin',
]);
Role::set('User',['Auth','HomeController']);
Role::set('Admin',['Auth','HomeController']);
Role::set('Visitor',['Auth','HomeController']);
