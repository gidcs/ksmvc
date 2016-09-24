<?php

class RoleClass {

	public $_name;
	private $_allowed_method = [];
	
	public function __construct($name){
		if(empty($name)){
			die("Error: Name in RoleClass cannot be blank!");
		}
		$this->_name = $name;
	}
	
	public function set_permissions($method){
		if(!count($method)){
			die("Error: method in RoleClass cannot be empty!");
		}
		foreach($method as $m){
			if(strcmp('Auth', $m)==0){
				$this->add('AuthController');
				$this->add('PasswordResetController');
			}
			else{
				$this->add($m);
			}
		}	
	}

	public function add($method){
		if(empty($method)){
			die("Error: method in RoleClass cannot be blank!");
		}
		if(strpos(',',$method)){
			echo $method.'\n';
			die("Error: comma should not be used in method add of RoleClass!");
		}
		//parse method
		if(strpos($method,'#')){
			$method = explode('#',$method);
		}
		else{
			$method = explode('@',$method);
		}
		if(count($method)==2){
			$controller=$method[0];
			$method=$method[1];
		}
		else{
			$controller=$method[0];
			$method="";
		}
		if(Route::exists($controller,$method)){ //check if method exists
			$this->_allowed_method[] = [$controller,$method];
		}
		else{
			die("Error: method($controller.$method) in RoleClass does not exist!");
		}
	}

	public function has_permission($controller, $method){
		foreach($this->_allowed_method as $m){
			if(strcmp($controller, $m[0])==0){
				if(empty($m[1]) || strcmp($method, $m[1])==0){
					//echo __CLASS__.".".__FUNCTION__." controller($controller) match.\n";
					return 1;
				}
			}
		}
		//echo __CLASS__.".".__FUNCTION__." controller($controller) mismatch.\n";
		return 0;
	}
	
	public function dump(){
		echo __CLASS__.".".__FUNCTION__." Role name: $this->_name.\n";
		var_dump($this->_allowed_method);
	}
}

class Role{
	static private $_role = [];
	static private $_user;
	static private $_user_role;
	private function __construct(){}	
	
	static public function find_index($role_name){
		foreach(self::$_role as $k=>$r){
			if(strcmp($r->_name,$role_name)==0){
				return $k;
			}
		}
		return -1;
	}

	static public function find_role_name($id){
		return self::$_role[$id]->_name;
	}

	static public function find($role_name){
		foreach(self::$_role as $r){
			if(strcmp($r->_name,$role_name)==0){
				return $r;
			}
		}
		return null;
	}
	
	static public function add($role = []){
		foreach($role as $role_name){
			$r = self::find($role_name);
			if(is_null($r)){
				self::$_role[] = new RoleClass($role_name);
			}
			else{
				die("Role($role_name) exists.\n");
			}
		}
	}
	
	static public function dump(){
		echo __CLASS__.".".__FUNCTION__." start...\n";
		foreach(self::$_role as $r){
			$r->dump();
			echo "\n";
		}
		echo __CLASS__.".".__FUNCTION__." end...\n";
	}
	
	static public function User(){
		if(self::$_user){
			return self::$_user;
		}
		if(!empty($token = Token::get())){
			if($token->username){
				self::$_user = User::where('username', $token->username)->first();
				return self::$_user;
			}
			return null;
		}
		else{
			return null;
		}
	}

	static public function check($controller, $method){
		$user = Role::User();
		if(!$user){
			self::$_user_role = Role::find('Visitor');
		}
		else{
			self::$_user_role = self::$_role[$user->role];
		}
		if(!self::$_user_role->has_permission($controller, $method)){
			die("Error: Permission denied.\n");
		}
	}

	static public function is_role($role_name){
		$user = Role::User();
		return ($user->role==Role::find_index($role_name));
	}
}
