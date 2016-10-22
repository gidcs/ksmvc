<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */

class RegisterController extends Controller
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

  public function get(){
    $data = [
      '_func' => 'Register'
    ];
    render('auth/common', $data);
  }
  
  public function post($post_params = []){
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
    
    redirect(Route::URI('Login#get'));
  }
}
