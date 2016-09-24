<?php

class Welcome extends Controller{
  
  public function index($params = ''){
    //$data = [ 'name' => User::find(1)->username ];
    $data = [ 
      'title' => 'KSMVC', 
    ];
    $this->view('welcome/index',$data);
  }
}
