<?php

use Firebase\JWT\JWT;
use Illuminate\Database\QueryException;

class AuthController extends Controller{
	
	public function _redirect_if_login(){
		if($this->get_user()){
			$this->redirect('/');
		}
	}
	
	public function _redirect_if_not_login(){
		if(!$this->get_user()){
			$this->redirect('/');
		}
	}
	
	public function get_user(){
		if(!empty($token = Token::get())){
			if($token->username){
				$user = User::where('username', $token->username)->first();
				return $user;
			}
			return null;
		}
		else{
			return null;
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
	
	public function getSettings(){
		$this->_redirect_if_not_login();
		$login_user = $this->get_user();
		$data['login_user'] = $login_user;
		$this->view('auth/getSettings', $data);
	}
	
	public function postSettings($post_params){
		$this->_redirect_if_not_login();
		$login_user = $this->get_user();
		$rules = [
			'email' => 'required|email|max:100',
			'password' => 'required|min:6|max:255|confirm'
		];
		$status = $this->validate($rules, $post_params);
		if($status->_status!=0){
			$data = [
				'login_user' => $login_user,
				'email' => $post_params['email'],
				'error' => $status->_message
			];
			$this->view('auth/getSettings', $data);
		}
		
		$temp_user = User::where('email',$post_params['email'])->first();
		if((!empty($temp_user)&&($temp_user->username!=$login_user->username))){
			$data = [
				'login_user' => $login_user,
				'email' => $post_params['email'],
				'error' => 'Hey, the email address is used.'
			];
			$this->view('auth/getSettings', $data);
		}
		
		$login_user->email = $post_params['email'];
		$login_user->password = password_hash($post_params['password'], PASSWORD_DEFAULT);
		$login_user->save();
		
		$data = [
			'login_user' => $login_user,
			'success' => 'Your information is updated now.'
		];
		$this->view('auth/getSettings', $data);
	}
	
	public function getLogin(){
		$this->_redirect_if_login();
		$this->view('auth/getLogin');
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
			$data = [
				'username' => $post_params['username'],
				'error' => $status->_message
			];
			$this->view('auth/getLogin', $data);
		}
		
		$user = User::where('username',$post_params['username'])->first();
		if(empty($user)){
			$data = [
				'username' => $post_params['username'],
				'error' => 'These credentials do not match our records.'
			];
			$this->view('auth/getLogin', $data);
		} 
		
		if(!password_verify($post_params['password'], $user->password)){
			$data = [
				'username' => $post_params['username'],
				'error' => 'These credentials do not match our records.'
			];
			$this->view('auth/getLogin', $data);
		}
		
		if(isset($post_params['remember'])){
			$this->login($user, 1);
		}
		else{
			$this->login($user);
		}
		
		$this->redirect('/');
	}
	
	public function getRegister(){
		$this->_redirect_if_login();
		$this->view('auth/getRegister');
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
			$data = [
				'username' => $post_params['username'],
				'email' => $post_params['email'],
				'error' => $status->_message
			];
			$this->view('auth/getRegister', $data);
		}
		
		$user = User::where('username',$post_params['username'])
					->orWhere('email',$post_params['email'])
					->first();
		if(!empty($user)){
			$data = [
				'username' => $post_params['username'],
				'email' => $post_params['email'],
				'error' => 'Hey, you had registered.'
			];
			$this->view('auth/getRegister', $data);
		}
		
		try {
			$user = User::create([
				'username' => $post_params['username'],
				'password' => password_hash($post_params['password'], PASSWORD_DEFAULT),
				'email' => $post_params['email']
			]);
		}
		catch (QueryException $e){
			$data = [
				'username' => $post_params['username'],
				'email' => $post_params['email'],
				'error' => 'We encounter some problem when processing your request.'
			];
			$this->view('auth/getRegister', $data);
		}
		
		//first user will be admin
		if($user->id==1){
			$user->is_admin = true;
			$user->save();
		}
		
		$this->login($user);
		
		$this->redirect('/');
	}
}
