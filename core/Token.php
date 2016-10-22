<?php

use Firebase\JWT\JWT;

class Token{
  private static $jwt_key = 'default_key';
  
  public static function boot(){
    $key = Option::where('name','jwt_key')->first();
    if(empty($key)){
      die("Error: JWT Token cannot be empty.");
    }
    self::$jwt_key = $key->value;
  }
  
  public static function encode($token=[]){
    return JWT::encode($token, self::$jwt_key);
  }
  
  public static function decode($token_string){
    try {
      $jwt = JWT::decode($token_string, self::$jwt_key, array('HS256'));
    }
    catch (Exception $e){
      Token::destroy();
      $jwt = null;
    }
    return $jwt;
  }
  
  public static function set($token=[], $remember=0){
    $jwt = self::encode($token);
    if($remember==1){
      setcookie("jwt", $jwt, time()+86400*30, '/'); //remember for 30 days
    }
    else{
      setcookie("jwt", $jwt, 0, '/');
    }
  }
  
  public static function get(){
    if (isset($_COOKIE['jwt'])){
      return self::decode($_COOKIE['jwt']);
    }
  }
  
  public static function destroy(){
    setcookie("jwt", "", time() - 3600, '/');
  }
}

