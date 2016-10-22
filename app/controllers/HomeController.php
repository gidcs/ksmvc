<?php

class HomeController extends Controller{
  
  public function index($params = ''){  
    render('home/index');
  }
}
