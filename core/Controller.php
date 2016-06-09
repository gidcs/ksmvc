<?php

class Controller{
	//The child class inherits all of the public and protected member of the parent class by using the extends keyword in the declaration.
	
	/*
	protected function model($model){
		$model_file='../app/Models/'.$model.'.php';
		file_checks($model_file);
		require_once($model_file);
		return new $model;
	}
	*/

	protected function view($view, $data=[]){	
		$view_file='../app/Views/'.$view.'.php';
		file_checks($view_file);
		require_once($view_file);
	}
}

