<?php

class HomeController extends Controller{
	
	public function index($params = ''){
		$data = [];
		if(!empty($token = Token::get())){
			$data['login_username'] = User::find($token->uid)->username;
		}	
		$this->view('home/index', $data);
	}
}
