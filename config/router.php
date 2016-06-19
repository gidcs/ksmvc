<?php

Route::get('/', 'home#index');
Route::get('/login', 'auth#getLogin');
Route::get('/register', 'auth#getRegister');

Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();