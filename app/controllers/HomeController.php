<?php

class HomeController extends Controller{
	
	public function index($params = ''){
		$auth = new AuthController;
		$data['username'] = $auth->get_username();
		$this->view('home/index', $data);
	}
}
