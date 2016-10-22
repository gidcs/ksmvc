<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */

class LoginController extends Controller
{

  public $_middleware = [
    [
      'CheckRole', [
        'role'=>'Visitor',
        'op' => '='
      ]
    ]
  ];

  private function page_error($_func, $error, $params){
    $data = [
      '_func' => $_func,
      'alert_error' => $error
    ];
    $data = array_merge($data, $params);
    render('auth/common', $data);
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

  public function get(){
    $data = [
      '_func' => 'Login'
    ];
    render('auth/common', $data);
  }

  public function post($post_params = []){
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
    
    redirect('/');
  }
}
