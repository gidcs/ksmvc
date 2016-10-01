<?php

use Pug\Pug as Pug;

class Controller{
  //The child class inherits all of the public and protected member of the parent class by using the extends keyword in the declaration.

  protected function redirect($url){
    header('Location: '.$url);
    exit();
  }
  
  protected function view($view, $data=[]){
    $view_file='../app/views/'.$view.'.php';
    file_checks($view_file);
    require_once($view_file);
    exit();
  }

  protected function render($view, $data=[]){
    $pug = new Pug([
      'prettyprint' => true,
      'extension' => '.pug'
    ]);
    $view_file='../app/views/'.$view.'.pug';
    if(!isset($data['alert_success'])) 
      $data['alert_success']='';
    if(!isset($data['alert_error']))
      $data['alert_error']='';
    $output = $pug->render($view_file, $data);
    echo $output;
    exit();
  }
  
  protected function includes($view, $data=[]){
    $view_file='../app/views/'.$view.'.php';
    file_checks($view_file);
    require_once($view_file);
  }
  
  protected function validate($rules=[], $post_params=[]){
    foreach($rules as $k => $v){
      //required
      if(empty($post_params[$k])){
        if(preg_match('#\brequired\b#i', $v)){
          return new ErrorMessage(1, "The $k field is required.");
        }
        else{
          continue;
        }
      }
      
      //pass this
      if(substr_exists($k, '_confirm')) continue; 
      
      //type
      if(preg_match('#\bemail\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_EMAIL) === false) {
            return new ErrorMessage(1, "The email must be a valid email address.");
          }
        }
      }
      else if(preg_match('#\bboolean\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_BOOLEAN) === false) {
            return new ErrorMessage(1, "The $k field must be boolean.");
          }
        }
      }
      else if(preg_match('#\bfloat\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_FLOAT) === false) {
            return new ErrorMessage(1, "The $k field must be float.");
          }
        }
      }
      else if(preg_match('#\bint\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_INT) === false) {
            return new ErrorMessage(1, "The $k field must be int.");
          }
        }
      }
      else if(preg_match('#\bip\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_IP) === false) {
            return new ErrorMessage(1, "The $k field must be ip address.");
          }
        }
      }
      else if(preg_match('#\burl\b#i', $v)){
        if(isset($post_params[$k])){
          if (filter_var($post_params[$k], FILTER_VALIDATE_URL) === false) {
            return new ErrorMessage(1, "The $k field must be url.");
          }
        }
      }
      
      //string length
      if(preg_match('#\bmax:(\d+)\b#i', $v, $matches)){
        if(isset($post_params[$k])){
          if(strlen($post_params[$k])>$matches[1]){
            return new ErrorMessage(1, "The $k field must be less than $matches[1] characters.");
          }
        }
      }
      if(preg_match('#\bmin:(\d+)\b#i', $v, $matches)){
        if(isset($post_params[$k])){
          if(strlen($post_params[$k])<$matches[1]){
            return new ErrorMessage(1, "The $k field must be at least $matches[1] characters.");
          }
        }
      }
      
      //confirm
      if(preg_match('#\bconfirm\b#i', $v)){
        if(isset($post_params[$k])){
          $tmp = $k.'_confirm';
          if(!isset($post_params[$tmp])){
            return new ErrorMessage(1, "The $k confirmation does not match.");
          }
          if($post_params[$tmp]!=$post_params[$k]){
            return new ErrorMessage(1, "The $k confirmation does not match.");
          }
        }
      }
    }
    return new ErrorMessage();
  }
  
  protected function paginate($obj, $limit, $page_id){
    $row_count = $obj->count();
    $max_page_size = intval(ceil($row_count/($limit)));
    if(!is_numeric($page_id)){
      $this->redirect('/');
    } 
    else if($page_id<1){
      $this->redirect(Route::URI(get_class($this).'#index')."");
    }
    else if($page_id>$max_page_size){
      $this->redirect(Route::URI(get_class($this).'#index')."/page/".$max_page_size);
    }
    return [ $max_page_size, $obj->take($limit)->offset(($page_id-1)*($limit))->get() ];
  }

  protected function numeric_check($id){
    if(!is_numeric($id)){
      $this->redirect('/');
    }
  }
}

