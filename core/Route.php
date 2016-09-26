<?php

//reference: http://www.lai18.com/content/6096271.html

class Route{
  static private $_route = [];
  static private $_uri = [];
  
  private function __construct(){}
 
  static public function boot(){
    //The running php file is in public directory.
    $config_file = '../config/router.php';
    file_checks($config_file);
    require_once($config_file);
  }

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
        'is_middleware' => ''
      ];
    }
    else{
      $controller_bak = $controller;
      $is_middleware = 0;
      $controller = ucfirst($controller);
      if(!isset(self::$_uri[$controller])){
        self::$_uri[$controller] = $uri;
      }
      if(strpos($controller,'#')){
        $controller = explode('#',$controller);
      }
      else{
        $controller = explode('@',$controller);
        $is_middleware = 1;
      }
      if(count($controller)!=2){
        die("Error: Controller ($controller_bak) is invalid!");
      }
      self::$_route[] = [
        'uri' => $uri,
        'request_method' => $request_method,
        'controller' => $controller[0],
        'method' => $controller[1],
        'is_middleware' => $is_middleware
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
    print 'request_method : uri'.'  ->  '.'controller#method'.'</br>'."\n";
    foreach(self::$_route as $v){
      if(!is_callable($v['controller'])){
        print $v['request_method'].' : '.$v['uri'].'  ->  '.$v['controller'].'#'.$v['method'].'</br>'."\n";
      }
      else{
        print $v['request_method'].' : '.$v['uri'].'  ->  '.self::closure_dump($v['controller']).'</br>'."\n";
      }
    }
  }

  static public function exists($controller, $method){
    $method='';
    if(empty($controller)){
      die("Error: controller in exists cannot be blank!");
    }
    foreach(self::$_route as $v){
      if(!is_callable($v['controller'])){
        if(strcmp($v['controller'],$controller)==0){
          if(empty($method)) return 1;
          else if(strcmp($v['method'],$method)==0) return 1;
        }
      }
    }
    return 0;
  }
  
  static public function match($req_method, $uri){
    //echo $uri."</br>";
    $param = [];
    foreach(self::$_route as $v){
      //check if parameter in v['uri']
      {
        $from = '#:\w+#';
        $to = '([0-9a-zA-Z.\-@]+)';
        preg_match_all($from, $v['uri'], $params_name);
        $pattern = '#^'.preg_replace($from,$to,$v['uri']).'$#';
      }
      //matching
      {
        if(preg_match($pattern, $uri, $matches)!=1) continue; //if no match on url
        if($req_method!=$v['request_method']) continue; //if no match on req_method
      }
      //get parameter
      {
        if(preg_match('/\b(GET|DELETE)\b/', $req_method)==0){
          $param['post_params'] = $_POST;
        }
        if(count($matches)>1){
          $i=1;
          foreach($params_name[0] as $name){
            $param[$name] = $matches[$i];
            $i++;
          }
        }
      }
      //Role check 
      Role::check($v['controller'],$v['method']);
      //preparing return object
      return new Router($v['controller'],$v['method'],$v['is_middleware'],$param);
    }
  }
  
  static public function Auth(){
    self::get('/login', 'AuthController@getLogin');
    self::post('/login', 'AuthController@postLogin');
    self::get('/logout', 'AuthController@getLogout');
    self::get('/register', 'AuthController@getRegister');
    self::post('/register', 'AuthController@postRegister');
    self::get('/profile', 'AuthController@getSettings');
    self::put('/profile', 'AuthController@putSettings');
    self::get('/password_reset', 'PasswordResetController@getPasswordReset');
    self::post('/password_reset', 'PasswordResetController@postPasswordReset');
    self::get('/password_reset/:email/:token', 'PasswordResetController@getPasswordResetActual');
    self::post('/password_reset/:email/:token', 'PasswordResetController@postPasswordResetActual');
  }
  
  static private function index($uri, $name){
    self::get($uri,$name.'#index');
    self::get($uri.'/page/:id', $name.'#index'); //for paginate
  }

  static private function create($uri, $name){ 
    self::get($uri.'/new',$name.'#create');
    self::post($uri.'/new',$name.'#store');
  }

  static private function show($uri, $name){
    self::get($uri.'/:id',$name.'#show');
  }

  static private function edit($uri, $name){
    self::get($uri.'/:id/edit',$name.'#edit');
    self::put($uri.'/:id/edit',$name.'#update');
  }

  static private function destroy($uri, $name){
    self::delete($uri.'/:id',$name.'#destroy');
  }

  static public function all($uri, $name='', $except=[]){
    if(empty($name)) $name = ucfirst(substr($uri,1)).'Controller';
    $func = [
      'index' => 1,
      'create' => 1,
      'show' => 1,
      'edit' => 1,
      'destroy' => 1
    ];
    if(isset($except['except'])){
      foreach($except['except'] as $e){
        if(array_key_exists($e, $func)){
          $func[$e] = 0;
        }
      }
    }
    foreach($func as $k=>$v){
      if($v) self::$k($uri, $name);
    }
  }

  static public function URI($controller){
    $uri = explode(":",self::$_uri[$controller]);
    return $uri[0];
  }

  static public function Method($method){
    return '<input id="_method" type="hidden" '
      .'class="form-control" name="_method" value="'
      .$method.'">'."\n";
  }
}

