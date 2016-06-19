<?php

Route::get('/', 'HomeController#index');
Route::get('/login', 'AuthController#getLogin');
Route::post('/login', 'AuthController#postLogin');
Route::get('/logout', 'AuthController#getLogout');
Route::get('/register', 'AuthController#getRegister');
Route::post('/register', 'AuthController#postRegister');
Route::get('/password_reset', 'PasswordResetController#getPasswordReset');
Route::post('/password_reset', 'PasswordResetController#postPasswordReset');
Route::get('/password_reset/:token', 'PasswordResetController#getPasswordResetActual');
Route::post('/password_reset/:token', 'PasswordResetController#postPasswordResetActual');

Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();