<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */

class LogoutController extends Controller
{

  public $_middleware = [
    [
      'CheckRole', [
        'role'=>'Visitor',
        'op' => '!='
      ]
    ]
  ];

  public function get(){
    Token::destroy();
    redirect('/');
  }

}
