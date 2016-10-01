<?php

use Firebase\JWT\JWT;
use Illuminate\Database\QueryException;

class AuthController extends Controller{  

  public function _redirect_if_login(){
    if(Role::User()){
      $this->redirect('/');
    }
  }
  
  public function _redirect_if_not_login(){
    if(!Role::User()){
      $this->redirect('/');
    }
  }
  
  public function login(User $user, $remember=0){
    $token = [
      'username' => $user->username
    ];
    if($remember){
      Token::set($token, 1);
    }
    else{ 
      Token::set($token);
    }
  }
  
  private function page_error($_func, $error, $params){
    $data = [
      '_func' => $_func,
      'alert_error' => $error
    ];
    $data = array_merge($data, $params);
    $this->render('auth/common', $data);
  }

  public function getProfile(){
    $this->_redirect_if_not_login();
    $user = Role::User();
    $data = [
      '_func' => 'Profile',
      'username' => $user->username,
      'email' => $user->email
    ];
    $this->render('auth/common', $data);
  }
  
  public function putProfile($post_params){
    $this->_redirect_if_not_login();
    $login_user = Role::User();
    $rules = [
      'email' => 'required|email|max:100',
      'password' => 'min:6|max:255|confirm'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Profile',
        $status->_message,
        $post_params
      );
    }
    
    $temp_user = User::where('email',$post_params['email'])->first();
    if((!empty($temp_user)&&($temp_user->username!=$login_user->username))){
      $this->page_error(
        'Profile',
        'Hey, the email address is used.',
        $post_params
      );
    }
    
    $login_user->email = $post_params['email'];
    if(!empty($post_params['password'])){
      $login_user->password = password_hash($post_params['password'], PASSWORD_DEFAULT);
    }
    $login_user->save();
    
    $data = [
      '_func' => 'Profile',
      'alert_success' => 'Your information is updated now.',
      'email' => $post_params['email']
    ];
    $this->render('auth/common', $data);
  }
  
  public function getLogin(){
    $this->_redirect_if_login();
    $data = [
      '_func' => 'Login'
    ];
    $this->render('auth/common', $data);
  }
  
  public function getLogout(){
    Token::destroy();
    $this->redirect('/');
  } 

  public function postLogin($post_params = []){
    $this->_redirect_if_login();
    $rules = [
      'username' => 'required|max:20|min:3',
      'password' => 'required|min:6|max:255'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Login',
        $status->_message,
        $post_params
      );
    }
    
    $user = User::where('username',$post_params['username'])->first();
    if(empty($user) || 
        !password_verify($post_params['password'], $user->password)
      ){
      $this->page_error(
        'Login',
        'These credentials do not match our records.',
        $post_params
      );
    } 
     
    if(!empty($post_params['remember'])){
      $this->login($user, 1);
    }
    else{
      $this->login($user);
    }
    
    $this->redirect('/');
  }
  
  public function getRegister(){
    $this->_redirect_if_login();
    $data = [
      '_func' => 'Register'
    ];
    $this->render('auth/common', $data);
  }
  
  public function postRegister($post_params = []){
    $this->_redirect_if_login();
    $rules = [
      'username' => 'required|max:20|min:3',
      'email' => 'required|email|max:100',
      'password' => 'required|min:6|max:255|confirm'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Register',
        $status->_message,
        $post_params
      );
    }
    
    $user = User::where('username',$post_params['username'])
          ->orWhere('email',$post_params['email'])
          ->first();
    if(!empty($user)){
      $this->page_error(
        'Register',
        'Hey, you had registered.',
        $post_params
      );
    }
    
    try {
      $user = User::create([
        'username' => $post_params['username'],
        'password' => password_hash($post_params['password'], PASSWORD_DEFAULT),
        'email' => $post_params['email'],
        'role' => Role::find_role_id('User')
      ]);
    }
    catch (QueryException $e){ 
      $this->page_error(
        'Register',
        'We encounter some problem when processing your request.',
        $post_params
      );
    }
    
    //first user will be admin
    if($user->id==1){
      $user->role = Role::find_role_id('Admin');
      $user->save();
    }
    
    $this->login($user);
    
    $this->redirect('/');
  }
}
