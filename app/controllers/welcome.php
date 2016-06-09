<?php

class Welcome extends Controller{
	
	public function index($params = ''){
		//$data = [ 'name' => User::find(1)->username ];
		//$this->view('welcome/index',$data);
		$this->view('welcome/index');
	}
}
