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
  static private $_role_id = []; //use role name as index
  static private $_count = 0;
  static private $_user;
  private function __construct(){}  
 
  /*
    find role id by role name
  */
  static public function find_role_id($role_name){
    if(isset(self::$_role_id[$role_name]))
      return self::$_role_id[$role_name];
    else
      return -1;
  }

  /*
    find role name by role id
  */
  static public function find_role_name($id){
    return self::$_role[$id]->_name;
  }

  /*
    find role obj by role name
  */
  static public function find($role_name){
    return self::find_role_obj($role_name);
  }
  static private function find_role_obj($role_name){
    return self::$_role[self::find_role_id($role_name)];
  }

  /* 
    new role by role name
  */
  static private function new_role($role_name){
    self::$_role_id[$role_name] = self::$_count;
    self::$_role[self::$_count] = new RoleClass($role_name);
    self::$_count++;
  }
 
  /*
    add roles
  */
  static public function add($role = []){
    foreach($role as $r){
      if(self::find_role_id($r)==-1){
        self::new_role($r);
      }
      else{
        die("Duplicate Role($r) detected.\n");
      }
    }
  }
 
  /*
    dump roles' information
  */
  static public function dump(){
    echo __CLASS__.".".__FUNCTION__." start...\n";
    foreach(self::$_role as $k=>$r){
      echo $k.' ';
      $r->dump();
      echo "\n";
    }

    foreach(self::$_role_id as $k=>$r){
      echo $k.' '.$r."\n";
    }
    echo __CLASS__.".".__FUNCTION__." end...\n";
  }
 
  /*
    get User obj
  */
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

  /*
    check permission to verify if user can access controller
  */
  static public function check($controller, $method){
    $user = self::User();
    $current_role = self::find_role_obj('Visitor');
    if($user){
      $current_role = self::$_role[$user->role];
    }
    if(!$current_role->has_permission($controller, $method)){
      die("Error: Permission denied.\n");
    }
  }

  /*
    check if user's role is $role_name
  */
  static public function is_role($role_name){
    $user = Role::User();
    return ($user->role==self::find_role_id($role_name));
  }
}
