<?php

Route::get('/', 'HomeController#index');
Route::Auth();

Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();