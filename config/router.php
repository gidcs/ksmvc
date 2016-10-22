<?php

Route::get('/', 'Home#index');
Route::Auth();
Route::all('/manage/users', 'Users', [
  'except' => ['show']
]);
Route::get('/manage/settings','Options#edit');
Route::put('/manage/settings/:name','Options#update');

//Route::print_route();
