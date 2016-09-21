<?php

class HomeController extends Controller{
	
	public function index($params = ''){
		$auth = new AuthController;
		$data['login_user'] = $auth->get_user();
		$this->view('home/index', $data);
	}
}
