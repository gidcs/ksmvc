<?php

use Firebase\JWT\JWT;
use Illuminate\Database\QueryException;

class AuthController extends Controller{
	
	public function _redirect_if_login(){
		if($this->get_username()){
			$this->redirect('/');
		}
	}
	
	public function _redirect_if_not_login(){
		if(!$this->get_username()){
			$this->redirect('/');
		}
	}
	
	public function get_username(){
		if(!empty($token = Token::get())){
			if($token->username){
				return $token->username;
			}
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
		$data['login_username'] = $this->get_username();
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
			$errorCode = $e->errorInfo[1];
			if($errorCode == 1062){
				$data = [
					'username' => $post_params['username'],
					'email' => $post_params['email'],
					'error' => 'We encounter some problem when processing your request.'
				];
				$this->view('auth/getRegister', $data);
			}
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
