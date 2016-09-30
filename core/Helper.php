<?php

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