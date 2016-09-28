<?php

Route::get('/', 'HomeController#index');
Route::Auth();
Route::all('/manage/users', 'UsersController', [
  'except' => ['show']
]);

//Route::print_route();
