<?php

/**
 *
 * you can use view method to display view when needed.
 * you can also pass variable to view via $data array. (optional)
 * 
 */

class UsersController extends Controller
{
  private $page_limit = 6;

  /**
   *
   * Display a listing of the resource.
   *
   */
  public function index($id=1)
  {
    $Obj = User::orderBy('id', 'desc');
    $paginate = $this->paginate($Obj, $this->page_limit, $id);
    $data = [
      'page_id' => $id,
      'max_id' => $paginate[0],
      'users' => $paginate[1],
    ];
    $this->render('users/index', $data);
  }

  private function page_error($_func, $error, $params){
    $data = [
        '_func' => $_func,
        'alert_error' => $error
      ];
    $data = array_merge($data, $params);
    $this->render('users/common', $data);
  }
  
  /** 
   *
   * Show the form for creating a new resource.
   *
   */  
  public function create()
  {   
    //
    $data = [
      '_func' => 'Create'
    ];
    $this->render('users/common', $data);  
  }
  
  /**
   * Store a newly created resource in storage.
   *
   * @param  array  $post_params = $_POST
   *
   */
  public function store($post_params)
  {
    //
    $rules = [
      'username' => 'required|max:20|min:3',
      'email' => 'required|email|max:100',
      'role' => 'int',
      'password' => 'min:6|max:255|confirm'
    ];

    //input checking
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Create',
        $status->_message,
        $post_params
      );
    }
   
    //username and email checking
    $temp_user = User::where('username',$post_params['username'])
                  ->orWhere('email',$post_params['email'])
                  ->first();
    if(!empty($temp_user)){ 
      $this->page_error(
        'Create',
        'Hey, the username or email address is used.',
        $post_params
      );
    }

    //create user 
    try {
      $user = User::create([
        'username' => $post_params['username'],
        'password' => password_hash($post_params['password'], PASSWORD_DEFAULT),
        'email' => $post_params['email'],
        'role' => $post_params['role']
      ]);
    }
    catch (QueryException $e){ 
      $this->page_error(
        'Create',
        'We encounter some problem when processing your request.',
        $post_params
      );
    }
    $this->redirect(Route::URI('UsersController#index'));
  }
  
  /**
   * Display the specified resource.
   *
   * @param  int  $id ( modify it to variable name match in router.php )
   *
   */
  public function show($id)
  {
    //useless
    $this->render('users/show', $data);
  }
  
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   *
   */
  public function edit($id)
  {
    $user = User::where('id', $id)->first();
    if(empty($user)){
      $this->redirect('/');
    }
    $data = [
      '_func' => 'Edit',
      'uid' => $user->id,
      'username' => $user->username,
      'email' => $user->email,
      'role' => $user->role,
    ];
    $this->render('users/common', $data);
  }
  
  /**
   * Update the specified resource in storage.
   *
   * @param  array  $post_params = $_POST
   * @param  int  $id
   *
   */
  public function update($post_params, $id)
  {
    $user = User::where('id', $id)->first();
    if(empty($user)){
      $this->redirect('/');
    }
    $rules = [
      'username' => 'required|max:20|min:3',
      'email' => 'required|email|max:100',
      'role' => 'int',
      'password' => 'min:6|max:255|confirm'
    ];

    //input checking
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Edit',
        $status->_message,
        $post_params
      );
    }
   
    //username and email checking
    $temp_user = User::where('username',$post_params['username'])
                  ->orWhere('email',$post_params['email'])
                  ->first();
    if((!empty($temp_user)&&($temp_user->id!=$user->id))){
      $this->page_error(
        'Edit',
        'Hey, the username or email address is used.',
        $post_params
      );
    }
    
    $user->username = $post_params['username'];
    $user->email = $post_params['email'];
    $user->role = $post_params['role'];
    if(!empty($post_params['password'])){
      $user->password = password_hash($post_params['password'], PASSWORD_DEFAULT);
    }
    $user->save();
    
    $data = [
      '_func' => 'Edit',
      'alert_success' => $user->username."'".'s information is updated now.'
    ];
    $data = array_merge($data, $post_params);
    $this->render('users/common', $data);
  }
  
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   *
   */
  public function destroy($id)
  {
    $this->numeric_check($id);
    $user=User::where('id', $id)->first();
    if($user) $user->delete();
    $this->redirect($_SERVER['HTTP_REFERER']);
  }
}
