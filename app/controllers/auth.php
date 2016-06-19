<?php

class Auth extends Controller{
	
	public function getLogin($params = ''){
		$this->view('auth/getLogin');
	}
	
	public function getRegister($params = ''){
		$this->view('auth/getRegister');
	}
}
