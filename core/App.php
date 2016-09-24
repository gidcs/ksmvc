<?php

class App{
  static private $instance;
  static private $router;
  static private $information;
  
  private function __construct(){}
  
  static public function instance() {
    if(!self::$instance) { 
      self::$instance = new App();
    }
    return self::$instance; 
  }
  
  public function setup($information=[]){
    self::$information = $information;
  }

  public function run(){
    self::match_uri();
    self::$router->run();
  }
  
  static public function info($name){
    return self::$information[$name];
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
    self::$router = Route::match(self::get_req_method(),self::get_uri());
    if(!self::$router){
      header('HTTP/1.1 404 Not Found');
      die("Error: Route Does not exist!");
    }
  }
  
}
