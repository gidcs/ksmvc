<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */

class PostsController extends Controller
{
  private $page_limit = 4;

  /*
   *
   * markdown parser
   *
   */
  private function _parsedown($post_content){
    $parsedown = new Parsedown();
    $post_content = $parsedown->text($post_content);
    $from[] = '#<([^>]*script)>#i';
    $to[] = '&lt;$1&gt;';
    $post_content = preg_replace($from,$to,$post_content); 
    return $post_content;
  }
  
  /**
   *
   * render error page
   *
   */
  private function page_error($_func, $error, $params){
    $data = [
      '_func' => $_func,
      'alert_error' => $error
    ];
    $data = array_merge($data, $params);
    $this->render('posts/common', $data);
  }

  /*
   *
   * get post object if id is valid
   *
   */
  private function Post($id){
    $this->numeric_check($id);
    $post=Post::where('id', $id)->first();
    if($post) 
      return $post;
    else
      $this->redirect('/');
  }

  /*
   *
   * redirect if no permission to edit/update/destroy
   *
   */
  private function permission_check($post, $flag=1){
    $admin = Role::is_role('Admin');
    $login_user = Role::User();
    if($admin){
      return 1;
    }
    else if($login_user->id==$post->user->id){
      return 1;
    }
    else{
      if(!$flag) return 0;
      else $this->redirect('/');
    }
  }
  
  /**
   *
   * Display a listing of the resource.
   *
   */
  public function index($id=1)
  {
    $Obj = Post::orderBy('id', 'desc');
    $paginate = $this->paginate($Obj, $this->page_limit, $id);
    $data = [
      'page_id' => $id,
      'max_id' => $paginate[0],
      'posts' => $paginate[1],
    ];

    $this->render('posts/index', $data);
  }
  
  /** 
   *
   * Show the form for creating a new resource.
   *
   */  
  public function create()
  {   
    $data = [
      '_func' => 'Create'
    ];
    $this->render('posts/common', $data);  
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
      'title' => 'required'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $this->page_error(
        'Create',
        $status->_message,
        $post_params
      );
    }
    
    //insert posts into db
    $login_user = Role::User();
    try {
      $post = $login_user->posts()->create([
        'title' => $post_params['title'],
        'content' => $post_params['content']
      ]);
    }
    catch (QueryException $e){
      $this->page_error(
        'Create',
        'We encounter some problem when processing your request.',
        $post_params
      );
    }
    //redirect to original page
    $this->redirect(Route::URI('PostsController#show').$post->id);
  }
  
  /**
   * Display the specified resource.
   *
   * @param  int  $id ( modify it to variable name match in router.php )
   *
   */
  public function show($id)
  {
    $post = $this->Post($id);
    $data = [
      'post_id' => $post->id,
      'post_title' => $post->title,
      'post_content' => $this->_parsedown($post->content),
      'post_author' => $post->user->username,
      'post_date' => $post->updated_at,
      'has_permission' => $this->permission_check($post, 0)
    ];
    $this->render('posts/show', $data);
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
    $post = $this->Post($id);
    $this->permission_check($post);
    $data = [
      '_func' => 'Edit',
      'pid' => $post->id,
      'title' => $post->title,
      'content' => $post->content,
      'author' => $post->user->username,
      'date' => $post->updated_at
    ];
    $this->render('posts/common', $data);
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
    $post = $this->Post($id);
    $this->permission_check($post);
    $rules = [
      'title' => 'required'
    ];
    $status = $this->validate($rules, $post_params);
    if($status->_status!=0){
      $post_params = array_merge($post_params,[
        'author' => $post->user->username,
        'date' => $post->updated_at
      ]);
      $this->page_error(
        'Edit',
        $status->_message,
        $post_params
      );
    }
    $post->title = $post_params['title'];
    $post->content = $post_params['content'];
    $post->save();

    $this->redirect(Route::URI('PostsController#show').$post->id);
  }
  
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   *
   */
  public function destroy($id)
  {
    //
    $post = $this->Post($id);
    $this->permission_check($post);
    $post->delete();
    $this->redirect($_SERVER['HTTP_REFERER']);
  }
}
