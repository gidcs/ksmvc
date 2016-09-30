<?php

//original route
Route::Auth();
Route::all('/manage/users', 'UsersController', [
  'except' => ['show']
]);
Route::get('/manage/settings','OptionsController#edit');
Route::put('/manage/settings/:name','OptionsController#update');

//posts route
Route::get('/', 'PostsController#index');
Route::get('/page/:id', 'PostsController#index');
Route::all('/posts');

//Route::print_route();
