<?php

use Illuminate\Database\QueryException;

class PasswordResetController extends Controller{
  
  function __construct(){
    $auth = new AuthController;
    $auth->_redirect_if_login();
  }
  
  public function getPasswordReset(){
    $this->view('password_reset/getPasswordReset');
  }
  
  public function postPasswordReset($post_params = []){
    $rules = [
      'email' => 'required|email|max:100',
    ];
    
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $data = [
        'email' => $post_params['email'],
        'error' => $status->_message
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    $user = User::where('email',$post_params['email'])->first();
    if(empty($user)){
      $data = [
        'email' => $post_params['email'],
        'error' => "Sorry, we couldn't find anyone with that email address or username."
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    //check if entry exists
    if($user->password_reset){
      //check if time vaild
      $now = new DateTime();
      $timeout_interval = new DateInterval('PT1H');
      $valid_datetime = new DateTime($user->password_reset->created_at);
      $valid_datetime->add($timeout_interval);
      if($valid_datetime > $now){
        $data = [
        'email' => $post_params['email'],
        'error' => "Sorry, you can only request once within 1 hour."
        ];
        $this->view('password_reset/getPasswordReset', $data);
      }
      else{
        $user->password_reset->delete();
      }
    }
    
    $token = sha1(uniqid($post_params['email'], true));
    
    try{
      PasswordReset::create([
        'email' => $post_params['email'],
        'token' => $token
      ]);
    }
    catch (QueryException $e){      
      $data = [
        'email' => $post_params['email'],
        'error' => 'We encounter some problem when processing your request.'
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    $receiver = $post_params['email'];
    $subject = "Password Reset Request";
    $mail_file='../app/mail/reset_password.html';
    file_checks($mail_file);
    $from = [ 
      'HTTP_HOST', 
      'RESET_PASSWORD_LINK',
      'WEBSITE_NAME_FOR_PASSWORD_RESET',
      'VALID_TIME_FOR_PASSWORD_RESET'
    ];
    $to = [
      App::info('protocol').App::info('domain_name'),
      App::info('protocol').App::info('domain_name').'/password_reset/'.$post_params['email'].'/'.$token,
      App::info('name'),
      '1 hour',
    ];
    $body = str_replace($from, $to, file_get_contents($mail_file));
    
    $status = Mail::send($receiver, $subject, $body);
    if($status->_status!=0){
      $data = [
        'error' => $status->_message
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    $data = [
      'success' => "We've sent an email to your email address. Click the link in the email to reset your password."
    ];
    $this->view('password_reset/getPasswordReset', $data);
  }
  
  public function getPasswordResetActual($email, $token){
    $entry = PasswordReset::where('email',$email)->where('token',$token)->first();
    //check if valid
    if(empty($entry)){
      $this->redirect('/');
    }
    
    //check if time vaild
    $now = new DateTime();
    $timeout_interval = new DateInterval('PT1H');
    $valid_datetime = new DateTime($entry->created_at);
    $valid_datetime->add($timeout_interval);
    if($valid_datetime < $now){
      $entry->delete();
      $data = [
        'error' => "Your token is invalid now. Please retrieve the token again."
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    $data = [
      'email' => $email,
      'token' => $token
    ];
    $this->view('password_reset/getPasswordResetActual', $data);
  }
  
  public function postPasswordResetActual($post_params = [], $email, $token){
    //check if password ok
    $rules = [
      'password' => 'required|min:6|max:255|confirm'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $data = [
        'token' => $token,
        'error' => $status->_message
      ];
      $this->view('password_reset/getPasswordResetActual', $data);
    }
    
    //check if token valid
    $entry = PasswordReset::where('email',$email)->where('token',$token)->first();
    if(empty($entry)){
      $this->redirect('/');
    }
    
    //check if time vaild
    $now = new DateTime();
    $timeout_interval = new DateInterval('PT1H');
    $valid_datetime = new DateTime($entry->created_at);
    $valid_datetime->add($timeout_interval);
    if($valid_datetime < $now){
      $data = [
        'error' => "Your token is invalid now. Please retrieve the token again."
      ];
      $this->view('password_reset/getPasswordReset', $data);
    }
    
    //change password and delete token
    $entry->user->password = password_hash($post_params['password'], PASSWORD_DEFAULT);
    $entry->user->save();
    $entry->delete();
    
    //login
    $auth = new AuthController;
    $auth->login($entry->user);
    $this->redirect('/');
  }
}
