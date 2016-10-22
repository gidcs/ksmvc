<?php

class Router{
  private $_controller;
  private $_method;
  private $_parameter;
  private $_is_middleware;
  
  public function __construct($controller = 'home',$method = 'index', $param=[]){
    if(is_callable($controller)){
      $this->_controller = $controller;
      $this->_method = '';
      $this->_parameter = $param;
    }
    else{
      $this->_controller = $controller.'Controller';
      $this->_method = $method;
      $this->_parameter = $param;
    }
  }
  public function run(){
    if(!is_callable($this->_controller)){
      {
        //controller/middleware setup
        $controller_file = '../app/controllers/'.$this->_controller.'.php';
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
        //try to get information about a function.
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
