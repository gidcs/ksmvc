<?php

Route::get('/', 'welcome#index');
Route::get('/welcome/:name', 
	function($name){
		print "Welcome to KSMVC, $name";
	}
);
//Route::print_route();