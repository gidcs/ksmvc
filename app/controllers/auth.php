<?php

use \Firebase\JWT\JWT;

class Auth extends Controller{
	
	public function getLogin(){
		$this->view('auth/getLogin');
	}
	
	public function getLogout(){
		Token::destroy();
		$this->redirect('/');
	}
	
	public function postLogin($post_params = []){
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
		
		$token = [
			'uid' => $user->id
		];
		
		if(isset($post_params['remember'])){
			Token::set($token, 1);
		}
		else{
			Token::set($token);
		}
		
		$this->redirect('/');
	}
	
	public function getRegister(){
		$this->view('auth/getRegister');
	}
	
	public function postRegister($post_params = []){
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
		
		User::create([
			'username' => $post_params['username'],
			'password' => password_hash($post_params['password'], PASSWORD_DEFAULT),
			'email' => $post_params['email']
		]);
		
		$this->redirect('/');
	}
}
