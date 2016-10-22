<?php

class ProfileController extends Controller{  

  public $_middleware = [
    [
      'CheckRole', [
        'role'=>'Visitor',
        'op' => '!='
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
    $user = Role::User();
    $data = [
      '_func' => 'Profile',
      'username' => $user->username,
      'email' => $user->email
    ];
    render('auth/common', $data);
  }
  
  public function put($post_params){
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
    render('auth/common', $data);
  }
  
}
