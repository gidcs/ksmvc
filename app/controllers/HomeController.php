<?php

class HomeController extends Controller{
	
	public function index($params = ''){
		$data = [];
		if(!empty($token = Token::get())){
			$data['login_username'] = User::find($token->uid)->username;
		}
		//$data = [ 'name' => User::find(1)->username ];		
		$this->view('home/index', $data);
	}
}
