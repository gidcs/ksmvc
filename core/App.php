<?php

class App{
	private $router;
	
	public function __construct(){
		$this->match_uri();
		$this->router->run();
	}
	
	private function get_req_method(){
		$request_method=$_SERVER["REQUEST_METHOD"];
		if(preg_match('/\b(GET|POST)\b/', $request_method)==0){
			die("Error: Request_method ($request_method) is invalid!");
		}
		if($request_method=='POST'){
			if(isset($_POST['_method'])){
				$_POST['_method']=strtoupper($_POST['_method']);
				if(preg_match('/\b(GET|POST|DELETE|PUT)\b/', $_POST['_method'])==1){
					$request_method=$_POST['_method'];
				}
			}
		}
		return $request_method;
	}
	
	private function get_uri(){
		$uri = '/';
		if(isset($_GET['url'])){
			//filter_var: FILTER_SANITIZE_URL filter removes all illegal URL characters from a string.
			//rtrim: Remove characters from the right side of a string using delimiter.		
			$uri = '/'.filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL);
		}
		return $uri;
	}
	
	private function match_uri(){
		$this->router = Route::match($this->get_req_method(),$this->get_uri());
		if(!$this->router){
			header('HTTP/1.1 404 Not Found');
			die("Error: Route Does not exist!");
		}
	}
	
}
