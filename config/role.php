<?php

Role::add([
  'Visitor',
  'User',
  'Operator',
  'Admin',
]);

$visitor_permissions = [
  'Auth',
  'HomeController',
];

$user_permissions = array_merge($visitor_permissions,[
]);

$operator_permissions = array_merge($user_permissions, [  
]);

$admin_permissions = array_merge($operator_permissions, [
  'OptionsController',
  'UsersController',
]);

Role::find('Visitor')->set_permissions($visitor_permissions);
Role::find('User')->set_permissions($user_permissions);
Role::find('Operator')->set_permissions($operator_permissions);
Role::find('Admin')->set_permissions($admin_permissions); 

