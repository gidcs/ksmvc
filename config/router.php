<?php

Route::get('/', 'HomeController#index');
Route::Auth();
Route::all('/manage/users', 'UsersController', [
  'except' => ['show']
]);

Route::get('/manage/settings','OptionsController#index');
//Route::print_route();
