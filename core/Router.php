<?php

class Router{
	private $_controller;
	public $_method;
	public $_parameter;
	public $_is_middleware;
	
	public function __construct($controller = 'home',$method = 'index', $is_middleware=0, $param=[]){
		if(is_callable($controller)){
			$this->_controller = $controller;
			$this->_method = '';
			$this->_parameter = $param;
			$this->_is_middleware = $is_middleware;
		}
		else{
			$this->_controller = $controller;
			$this->_method = $method;
			$this->_parameter = $param;
			$this->_is_middleware = $is_middleware;
		}
	}
	public function run(){
		if(!is_callable($this->_controller)){
			{
				//controller/middleware setup
				if($this->_is_middleware){
					$controller_file = '../app/middleware/'.$this->_controller.'.php';
				}
				else{
					$controller_file = '../app/controllers/'.$this->_controller.'.php';
				}
				//check if controller/middleware exists
				file_checks($controller_file);
				//include controller and create a controller object
				require_once($controller_file);	
				$this->_controller = new $this->_controller;
				//checks if the class method exists
				method_checks($this->_controller,$this->_method);
			}
			call_user_func_array(array($this->_controller,$this->_method),$this->_parameter);
		}
		else{
			try {  
				$func = new ReflectionFunction($this->_controller);  
			} catch (ReflectionException $e) {  
				echo $e->getMessage();  
				return;  
			}
			if($func->getNumberOfParameters()){
				call_user_func_array($this->_controller,$this->_parameter);
			}
			else{
				call_user_func($this->_controller);
			}
		}
	}
}