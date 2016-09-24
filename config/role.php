<?php

Role::add([
  'Visitor',
  'User',
  'Operator',
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

Role::find('Operator')->set_permissions([
  'Auth',
  'HomeController',
]);

Role::find('Admin')->set_permissions([
  'Auth',
  'HomeController',
  'UsersController'
]);

