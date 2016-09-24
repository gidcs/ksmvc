<?php

/**
 *
 * you can use view method to display view when needed.
 * you can also pass variable to view via $data array. (optional)
 * 
 */

class UsersController extends Controller
{
  private $page_limit = 7;

  /**
   *
   * Display a listing of the resource.
   *
   */
  public function index($id=1)
  {
    $Obj = User::orderBy('id', 'asc');
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
  }
  
  /**
   * Display the specified resource.
   *
   * @param  int  $id ( modify it to variable name match in router.php )
   *
   */
  public function show($id)
  {
    //
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
    //
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
    //
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
