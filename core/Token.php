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
  
  static public function encode($token=[]){
    return JWT::encode($token, self::$jwt_key);
  }
  
  static public function decode($token_string){
    try {
      $jwt = JWT::decode($token_string, self::$jwt_key, array('HS256'));
    }
    catch (Exception $e){
      Token::destroy();
      $jwt = null;
    }
    return $jwt;
  }
  
  static public function set($token=[], $remember=0){
    $jwt = self::encode($token);
    if($remember==1){
      setcookie("jwt", $jwt, time()+86400*30, '/'); //remember for 30 days
    }
    else{
      setcookie("jwt", $jwt, 0, '/');
    }
  }
  
  static public function get(){
    if (isset($_COOKIE['jwt'])){
      return self::decode($_COOKIE['jwt']);
    }
  }
  
  static public function destroy(){
    setcookie("jwt", "", time() - 3600, '/');
  }
}

