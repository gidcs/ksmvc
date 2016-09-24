<?php

Route::get('/welcome/:name', 
  function($name){
    print "Welcome to KSMVC, $name";
  }
);
Route::get('/', 'HomeController#index');
Route::Auth();
Route::all('/manage/users', 'UsersController');

//Route::print_route();
