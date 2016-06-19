<?php

class Home extends Controller{
	
	public function index($params = ''){
		//$data = [ 'name' => User::find(1)->username ];
		$this->view('home/index');
	}
}
