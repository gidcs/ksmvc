<?php

Route::get('/', 'HomeController#index');
Route::Auth();
Route::all('/manage/users', 'UsersController', [
  'except' => ['show']
]);

Route::get('/manage/settings','OptionsController#edit');
Route::put('/manage/settings/:name','OptionsController#update');
//Route::print_route();
