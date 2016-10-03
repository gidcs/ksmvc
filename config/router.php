<?php

Route::Auth();
Route::all('/manage/users', 'UsersController', [
  'except' => ['show']
]);
Route::get('/manage/settings','OptionsController#edit');
Route::put('/manage/settings/:name','OptionsController#update');

Route::get('/', 'PostsController#index');
Route::get('/page/:id', 'PostsController#index');
Route::all('/posts', 'PostsController');

//Route::print_route();
