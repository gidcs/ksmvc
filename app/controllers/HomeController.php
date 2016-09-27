<?php

class HomeController extends Controller{
  
  public function index($params = ''){ 
    $this->render('home/index');
  }
}
