<?php

//reference: http://www.lai18.com/content/6096271.html

class Route{
	static private $_route = [];
	
	private function __construct(){}
	
	static public function get($uri='',$controller=''){
		self::add('GET',$uri,$controller);
	}
	
	static public function post($uri='',$controller=''){
		self::add('POST',$uri,$controller);
	}
	
	static public function delete($uri='',$controller=''){
		self::add('DELETE',$uri,$controller);
	}
	
	static public function put($uri='',$controller=''){
		self::add('PUT',$uri,$controller);
	}
	
	static private function add($request_method='GET',$uri='',$controller=''){
		if(preg_match('/\b(GET|POST|DELETE|PUT)\b/', $request_method)==0){
			die("Error: Request_method ($request_method) is invalid!");
		}
		if(is_callable($controller)){
			self::$_route[] = [
				'uri' => $uri,
				'request_method' => $request_method,
				'controller' => $controller,
				'method' => '',
			];
		}
		else{
			$controller = explode('#',ucfirst($controller));
			if(count($controller)!=2){
				$controller = implode('#', $controller);
				die("Error: Controller ($controller) is invalid!");
			}
			self::$_route[] = [
				'uri' => $uri,
				'request_method' => $request_method,
				'controller' => $controller[0],
				'method' => $controller[1],
			];
		}
	}
	
	static private function closure_dump(Closure $c) {
		try {  
			$func = new ReflectionFunction($c);  
		} catch (ReflectionException $e) {  
			echo $e->getMessage();  
			return;  
		}
		$start = $func->getStartLine()-1;
		$end =  $func->getEndLine()-1;
		$filename = $func->getFileName();
		return implode("", array_slice(file($filename),$start, $end - $start + 1));  
	}
	
	static public function print_route(){
		print 'request_method : uri'.'  ->  '.'controller#method or function'.'</br>'."\n";
		foreach(self::$_route as $v){
			if(empty($v['function'])){
				print $v['request_method'].' : '.$v['uri'].'  ->  '.$v['controller'].'#'.$v['method'].'</br>'."\n";
			}
			else{
				print $v['request_method'].' : '.$v['uri'].'  ->  '.self::closure_dump($v['function']).'</br>'."\n";
			}
		}
	}
	
	static public function match($req_method, $uri){
		//echo $uri."</br>";
		$param = [];
		foreach(self::$_route as $v){
			//check if parameter in v['uri']
			{
				$v['uri'] = explode(':',$v['uri']);
				$pattern = '#^'.$v['uri'][0];
				$cnt = count($v['uri']);
				$flag = 0; //user for add '/' when parameter more than 1
				while(--$cnt){
					if($flag==0) $flag=1;
					else $pattern.='/';
					$pattern.='[0-9a-zA-Z.\-]+';
				}
				$pattern.='$#'; //$ = null character
			}
			//matching
			{
				if(preg_match($pattern, $uri)!=1) continue; //if no match on url
				if($req_method!=$v['request_method']) continue; //if no match on req_method
			}
			//get parameter
			{
				if(preg_match('/\b(GET)\b/', $req_method)==0){
					$param['post_params'] = $_POST;
				}
				if(count($v['uri'])>1) {
					$rest = substr($uri,1); //remove '/' character
					$tmp_param = explode('/',$rest);
					//var_dump($tmp_param);
					foreach($v['uri'] as $i => $value){
						if($i!=0){
							$name=explode('/',$value)[0];
							$param[$name] = $tmp_param[$i];
						}
					}
				}
			}
			//preparing return object
			return new Router($v['controller'],$v['method'],$param);
		}
	}
	
	static public function Auth(){
		self::get('/login', 'AuthController#getLogin');
		self::post('/login', 'AuthController#postLogin');
		self::get('/logout', 'AuthController#getLogout');
		self::get('/register', 'AuthController#getRegister');
		self::post('/register', 'AuthController#postRegister');
		self::get('/password_reset', 'PasswordResetController#getPasswordReset');
		self::post('/password_reset', 'PasswordResetController#postPasswordReset');
		self::get('/password_reset/:token', 'PasswordResetController#getPasswordResetActual');
		self::post('/password_reset/:token', 'PasswordResetController#postPasswordResetActual');
	}
}
