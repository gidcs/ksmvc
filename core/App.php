<?php

class App{
  private static $_instance;
  private static $_router;
  private static $_information;
  
  private function __construct(){}
  
  public static function instance() {
    if(!self::$_instance) { 
      self::$_instance = new App();
    }
    return self::$_instance; 
  }
  
  public function boot(){
    $site = Option::where('name','LIKE','site_%')->get(); 
    if(empty($site)){
      die("Error: Site's settings is not completed yet.");
    }
    foreach($site as $s){
      self::$_information[$s->name] = $s->value;    
    }
  }

  public function run(){
    self::match_uri();
    self::$_router->run();
  }
  
  public static function info($name){
    return self::$_information['site_'.$name];
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
    self::$_router = Route::match(self::get_req_method(),self::get_uri());
    if(!self::$_router){
      header('HTTP/1.1 404 Not Found');
      die("Error: Route Does not exist!");
    }
  }
  
}
