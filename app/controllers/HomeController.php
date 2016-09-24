<?php

class HomeController extends Controller{
  
  public function index($params = ''){
    $data['login_user'] = Role::User();
    $this->view('home/index', $data);
  }
}
