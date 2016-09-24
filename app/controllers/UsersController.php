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
      'login_user' => Role::User(),  
      'page_id' => $id,
      'max_id' => $paginate[0],
      'users' => $paginate[1],
    ];
    $this->view('users/index', $data);
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
      'login_user' => Role::User()
    ];
    $this->view('users/create', $data);  
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
    $login_user = Role::User(); 
    $rules = [
      'username' => 'required|max:20|min:3',
      'email' => 'required|email|max:100',
      'role' => 'required|int',
      'password' => 'required|min:6|max:255|confirm'
    ];

    //input checking
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $data = [
        'login_user' => $login_user,
        'error' => $status->_message
      ];
      $data = array_merge($data, $post_params);
      $this->view('users/create', $data);
    }
   
    //username and email checking
    $temp_user = User::where('username',$post_params['username'])
                  ->orWhere('email',$post_params['email'])
                  ->first();
    if(!empty($temp_user)){
      $data = [
        'login_user' => $login_user,
        'error' => 'Hey, the username or email address is used.'
      ];
      $data = array_merge($data, $post_params);
      $this->view('users/create', $data);
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
      $data = [
        'login_user' => $login_user,
        'error' => 'We encounter some problem when processing your request.'
      ];
      $data = array_merge($data, $post_params);
      $this->view('users/create', $data);
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
    $this->view('users/show', $data);
  }
  
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   *
   */
  public function edit($id)
  {
    $data = [
      'login_user' => Role::User(),
      'user' => User::where('id', $id)->first()
    ];
    $this->view('users/edit', $data);
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
    $login_user = Role::User();
    $user = User::where('id', $id)->first();
    $rules = [
      'username' => 'required|max:20|min:3',
      'email' => 'required|email|max:100',
      'role' => 'required|int',
      'password' => 'min:6|max:255|confirm'
    ];

    //input checking
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $data = [
        'login_user' => $login_user,
        'user' => $user,
        'error' => $status->_message
      ];
      $this->view('users/edit', $data);
    }
   
    //username and email checking
    $temp_user = User::where('username',$post_params['username'])
                  ->orWhere('email',$post_params['email'])
                  ->first();
    if((!empty($temp_user)&&($temp_user->id!=$user->id))){
      $data = [
        'login_user' => $login_user,
        'user' => $user,
        'error' => 'Hey, the username or email address is used.'
      ];
      $this->view('users/edit', $data);
    }
    
    $user->username = $post_params['username'];
    $user->email = $post_params['email'];
    $user->role = $post_params['role'];
    if(!empty($post_params['password'])){
      $user->password = password_hash($post_params['password'], PASSWORD_DEFAULT);
    }
    $user->save();
    
    $data = [
      'login_user' => $login_user,
      'user' => $user,
      'success' => $user->username."'".'s information is updated now.'
    ];
    $this->view('users/edit', $data);
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
