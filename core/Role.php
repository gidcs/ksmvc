<?php

class Role{
  private static $_role = [];
  private static $_role_id = []; //use role name as index
  private static $_count = 0;
  private static $_user;
  private function __construct(){}  

  /*
    boot up function
  */
  public static function boot(){
    //The running php file is in public directory.
    $config_file = '../config/role.php';
    file_checks($config_file);
    require_once($config_file);
  }

  /*
    get all role
  */
  public static function All(){
    $roles = [];
    foreach(self::$_role as $r)
      $roles[] = $r;
    return $roles;
  }

  /*
    find role id by role name
  */
  public static function find_role_id($role_name){
    if(isset(self::$_role_id[$role_name]))
      return self::$_role_id[$role_name];
    else
      return -1;
  }

  /*
    find role name by role id
  */
  public static function find_role_name($id){
    return self::$_role[$id];
  }

  /* 
    new role by role name
  */
  private static function new_role($role_name){
    self::$_role_id[$role_name] = self::$_count;
    self::$_role[self::$_count] = $role_name;
    self::$_count++;
  }
 
  /*
    add roles
  */
  public static function add($role = []){
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
  public static function dump(){
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
  public static function User(){
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
    check if user's role is $role_name
  */
  public static function is_role($role_name){
    return self::role_check($role_name, '=');
  }

  public static function role_check($role_name, $op='='){
    $user = self::User();
    $current_role = self::find_role_id('Visitor');  
    if($user){
      $current_role = $user->role;
    }
    return dynamic_compare($current_role, $op, self::find_role_id($role_name));
  }
}
