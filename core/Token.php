<?php

use \Firebase\JWT\JWT;

class Token{
	static private $jwt_key = 'default_key';
	
	static public function key($key){
		if($key==""){
			die("Error: JWT Token cannot be empty string.");
		}
		self::$jwt_key = $key;
	}
	
	static public function set($token, $remember=0){
		$jwt = JWT::encode($token, self::$jwt_key);
		if($remember==1){
			setcookie("jwt", $jwt, time()+86400*30); //remember for 30 days
		}
		else{
			setcookie("jwt", $jwt);
		}
	}
	
	static public function get(){
		if (isset($_COOKIE['jwt'])){
			return JWT::decode($_COOKIE['jwt'], self::$jwt_key, array('HS256'));
		}
	}
	
	static public function destroy(){
		setcookie("jwt", "", time() - 3600);
	}
}

