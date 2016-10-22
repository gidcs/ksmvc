<?php

class CheckRole {
  
  public function handle($role, $op){ 
    if(!Role::role_check($role, $op)){
      redirect('/');
    }
  }
}
