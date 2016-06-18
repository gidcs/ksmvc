<?php

class Controller{
	//The child class inherits all of the public and protected member of the parent class by using the extends keyword in the declaration.
	
	/*
	protected function model($model){
		$model_file='../app/models/'.$model.'.php';
		file_checks($model_file);
		require_once($model_file);
		return new $model;
	}
	*/

	protected function view($view, $data=[]){
		$from[] = '#\@extend\([\'"](.+?)[\'"]\)#e';
		$to[] = "file_get_contents_checks('../app/views/$1.php')";
		foreach($data as $k => $v){
			$from[] = '#\<\?\=\$data\[[\'"]'.$k.'[\'"]\]\?\>#';
			$to[] = $v;
		}
		$view_file='../app/views/'.$view.'.php';
		file_checks($view_file);
		$view_contents = file_get_contents($view_file);
		echo preg_replace($from,$to,$view_contents);
	}
}

