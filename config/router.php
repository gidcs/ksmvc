<?php

Route::get('/', 'Home#index');
Route::Auth();
Route::all('/manage/users', 'Users', [
  'except' => ['show']
]);
Route::get('/manage/settings','Options#edit');
Route::put('/manage/settings','Options#update');

Route::post('/test', function(){
  $data = array();
  $data['message'] = $_POST;
  echo json_encode($data);
});

//Route::print_route();
