<?php

Route::get('/', 'home#index');
Route::get('/login', 'auth#getLogin');
Route::post('/login', 'auth#postLogin');
Route::get('/logout', 'auth#getLogin');
Route::get('/register', 'auth#getRegister');
Route::post('/register', 'auth#postRegister');

Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();