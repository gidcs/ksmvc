<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */


class OptionsController extends Controller
{
  
  public $_middleware = [
    [
      'CheckRole', [
        'role'=>'Admin',
        'op' => '>='
      ]
    ]
  ];
  
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   *
   */
  public function edit()
  {
    $site = Option::where('name','LIKE','site_%')->get();
    $jwt = Option::where('name','LIKE','jwt_%')->get();
    $smtp = Option::where('name','LIKE','smtp_%')->get();
    foreach($smtp as $o){
      if($o->name=='smtp_password')
        $o->value = ''; 
    }
    $data = [
      'site' => $site,
      'jwt' => $jwt,
      'smtp' => $smtp
    ];
    render('options/common', $data);
  }
  
  /**
   * Update the specified resource in storage.
   *
   * @param  array  $post_params = $_POST
   * @param  string  $name
   *
   */
  public function update($post_params, $name)
  {
    if(!is_string($name)){
      redirect('/');
    }
    
    switch ($name) {
      case "site" :
      case "jwt" :
      case "smtp" :
        foreach($post_params as $k=>$v){
          if($k=='_method') continue;
          if(strpos($k, $name)===false) 
            redirect('/');
          $option = Option::where('name',$k)->first();
          if(empty($option)) redirect('/');
          
          //value checking
          switch ($k){
            case "site_protocol" :
              if($v!="http" && $v!="https")
                redirect('/');
              break; 
            case "smtp_auth":
              if($v!="0" && $v!="1")
                redirect('/');
              break;
            case "smtp_secure":
              if($v!="tls" && $v!="ssl") 
                redirect('/');
              break;
            case "smtp_password" : 
              if(empty($v)) continue 2;
              break;
            default:

          }

          //store value
          if($k=='smtp_password'){
            $option->value = base64_encode($v);
          }
          else{
            $option->value = $v;
          }
          $option->save();
        }
        if($name=="jwt")
          redirect('/');
        else
          redirect($_SERVER['HTTP_REFERER']);
        break;
      default:
        redirect('/');
    }
  } 
}
