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

	protected function redirect($url){
		header('Location: '.$url);
		exit();
	}
	
	protected function view($view, $data=[]){
		$view_file='../app/views/'.$view.'.php';
		file_checks($view_file);
		require_once($view_file);
		exit();
	}
	
	protected function includes($view, $data=[]){
		$view_file='../app/views/'.$view.'.php';
		file_checks($view_file);
		require_once($view_file);
	}
	
	protected function new_view_engine($view, $data=[]){
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
	
	protected function validate($rules=[], $post_params=[]){
		foreach($rules as $k => $v){
			//pass this
			if($k=='password-confirm') continue;
			
			//required
			if(preg_match('#\brequired\b#i', $v)){
				if(!isset($post_params[$k])){
					return new ErrorMessage(1, "The $k field is required.");
				}
				if($post_params[$k]==""){
					return new ErrorMessage(1, "The $k field is required.");
				}
			}
			
			//type
			if(preg_match('#\bemail\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_EMAIL) === false) {
						return new ErrorMessage(1, "The email must be a valid email address.");
					}
				}
			}
			else if(preg_match('#\bboolean\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_BOOLEAN) === false) {
						return new ErrorMessage(1, "The $k field must be boolean.");
					}
				}
			}
			else if(preg_match('#\bfloat\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_FLOAT) === false) {
						return new ErrorMessage(1, "The $k field must be float.");
					}
				}
			}
			else if(preg_match('#\bint\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_INT) === false) {
						return new ErrorMessage(1, "The $k field must be int.");
					}
				}
			}
			else if(preg_match('#\bip\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_IP) === false) {
						return new ErrorMessage(1, "The $k field must be ip address.");
					}
				}
			}
			else if(preg_match('#\burl\b#i', $v)){
				if(isset($post_params[$k])){
					if (filter_var($post_params[$k], FILTER_VALIDATE_URL) === false) {
						return new ErrorMessage(1, "The $k field must be url.");
					}
				}
			}
			
			//string length
			if(preg_match('#\bmax:(\d+)\b#i', $v, $matches)){
				if(isset($post_params[$k])){
					if(strlen($post_params[$k])>$matches[1]){
						return new ErrorMessage(1, "The $k field must be less than $matches[1] characters.");
					}
				}
			}
			if(preg_match('#\bmin:(\d+)\b#i', $v, $matches)){
				if(isset($post_params[$k])){
					if(strlen($post_params[$k])<$matches[1]){
						return new ErrorMessage(1, "The $k field must be at least $matches[1] characters.");
					}
				}
			}
			
			//confirm
			if(preg_match('#\bconfirm\b#i', $v)){
				if(isset($post_params[$k])){
					$tmp = $k.'_confirmation';
					if(!isset($post_params[$tmp])){
						return new ErrorMessage(1, "The $k confirmation does not match.");
					}
					if($post_params[$tmp]!=$post_params[$k]){
						return new ErrorMessage(1, "The $k confirmation does not match.");
					}
				}
			}
		}
		return new ErrorMessage();
	}
	
	protected function replace_special_char($string){
		return htmlentities($string, ENT_QUOTES | ENT_IGNORE, "UTF-8");
	}
	
	protected function replace_script($string){
		$from[] = '#<([^>]*script)>#i';
		$to[] = '&lt;$1&gt;';
		return preg_replace($from,$to,$string);
	}
}

