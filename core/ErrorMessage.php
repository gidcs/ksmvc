<?php

class ErrorMessage{
  public $_status;
  public $_message;
  public function __construct($status = 0, $message = 'OK.'){
    $this->_status = $status;
    $this->_message = $message;
  }  
}