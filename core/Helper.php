<?php

use Pug\Pug as Pug;

function file_checks($file, $error=true){
  if(!file_exists($file)){
    if($error) die("Error: File ($file) does not exists.");
  }
  return 1;
}

function file_get_contents_checks($file, $error=true){
  if(!file_exists($file)){
    if($error) die("Error: File ($file) does not exists.");
  }
  return file_get_contents($file);
}

function method_checks($controller, $method, $error=true){
  if(!method_exists($controller,$method)){
    if($error) die("Error: Method ($method) does not exists.");
  }
  return 1;
}

function substr_exists($text, $substr){
  if(strpos($text, $substr)!==false){
    return true;
  }
  else{
    return false;
  }
}

function dynamic_compare($var1, $op, $var2){
  switch ($op) {
    case "=":  return $var1 == $var2;
    case "!=": return $var1 != $var2;
    case ">=": return $var1 >= $var2;
    case "<=": return $var1 <= $var2;
    case ">":  return $var1 >  $var2;
    case "<":  return $var1 <  $var2;
    default:       return true;
  }   
}

function redirect($url){
  header('Location: '.$url);
  exit();
}

function view($view, $data=[]){
  $view_file='../app/views/'.$view.'.php';
  file_checks($view_file);
  require_once($view_file);
  exit();
}

function render($view, $data=[]){
  global $env;
  if($env=='development'){
    $pug =  new Pug([
      'prettyprint' => true,
      'extension' => '.pug',
    ]);
  }
  else {
    $pug = new Pug([
      'prettyprint' => true,
      'extension' => '.pug',
      'cache' => '../app/cache/'
    ]);
  }
  $view_file='../app/views/'.$view.'.pug';
  if(!isset($data['alert_success'])) 
    $data['alert_success']='';
  if(!isset($data['alert_error']))
    $data['alert_error']='';
  if(!isset($data['referer']))
    $data['referer']=(empty($_SERVER['HTTP_REFERER']))?'':$_SERVER['HTTP_REFERER'];
  $output = $pug->render($view_file, $data);
  echo $output;
  exit();
}

function includes($view, $data=[]){
  $view_file='../app/views/'.$view.'.php';
  file_checks($view_file);
  require_once($view_file);
}
