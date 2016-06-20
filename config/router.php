<?php

Route::get('/', 'PostsController#index');
Route::get('/page/:id', 'PostsController#index');

Route::Auth();

Route::all('/posts');

Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();