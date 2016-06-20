<?php

/**
 *
 * you can use view method to display view when needed.
 * you can also pass variable to view via $data array. (optional)
 * 
 */

class PostsController extends Controller
{
	protected $_auth;
	
	public function __construct(){
		$this->_auth = new AuthController;
	}
	/**
	 *
	 * Display a listing of the resource.
	 *
	 */
	public function index($id=1)
	{
		if(!is_numeric($id)){
			$this->redirect('/');
		}
		$row_count = Post::count();
		$pagesize = 4;
		$page = $id;
		$max_page_size = intval(ceil($row_count/$pagesize));
		if($id<1){
			$this->redirect('/');
		}
		else if($id>$max_page_size && $id!=1){
			$this->redirect('/page/'.$max_page_size);
		}
		$page = min($max_page_size,$page);
		$posts = Post::orderBy('id', 'desc')
				->take($pagesize)->offset(($page-1)*$pagesize)->get();
		$show_posts = [];
		foreach($posts as $post){
			$show_posts[] = [
				'id' => $post->id,
				'title' => (strlen($post->title)>=50)?substr($post->title,0,50).'...':$post->title,
				'owner' => $post->user->username,
				'date' => date('M d, Y', strtotime($post->updated_at)),
			];
		}
		$data = [
			'login_user' => $this->_auth->get_username(),
			'posts' => $show_posts,
			'page_id' => $page,
			'max_id' => $max_page_size,
		];
		$this->view('posts/index', $data);
	}
	
	/** 
	 *
     * Show the form for creating a new resource.
	 *
     */  
    public function create()
	{   
		$this->_auth->_redirect_if_not_login();
        //
		$data = [
			'login_user' => $this->_auth->get_username(),
		];
		$this->view('posts/create', $data);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  array  $post_param = $_POST
     *
     */
    public function store($post_params)
    {
		$this->_auth->_redirect_if_not_login();
		//check input
		$rules = [
			'title' => 'required'
		];
		$status = $this->validate($rules, $post_params);
        if($status->_status!=0){
			$data = [
				'login_user' => $this->get_username(),
				'title' => $post_params['title'],
				'error' => $status->_message
			];
			$this->view('posts/create', $data);
		}
		
		$user = User::where('username',$this->_auth->get_username())->first();
		
		//insert post
		try {
			$post = $user->post()->create([
				'title' => $post_params['title'],
				'content' => $post_params['content']
			]);
		}
		catch (QueryException $e){
			$data = [
				'login_user' => $this->_auth->get_username(),
				'title' => $post_params['title'],
				'content' => $post_params['content'],
				'error' => 'We encounter some problem when processing your request.'
			];
			$this->view('posts/create', $data);
		}
		
		$this->redirect('/');
    }
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id ( modify it to variable name match in router.php )
	 *
     */
    public function show($id)
    {
		if(!is_numeric($id)){
			$this->redirect('/');
		}
		$user = User::where('username',$this->_auth->get_username())->first();
		$post = Post::where('id', $id)->first();
		if(empty($post)){
			$this->redirect('/');
		}
		if($user){
			$has_permission=($user->username==$post->user->username)?1:($user->is_admin==true)?1:0;
		}
		else{
			$has_permission=0;
		}
		$Parsedown = new Parsedown();
		$data = [
			'login_user' => $this->_auth->get_username(),
			'id' => $post->id,
			'title' => $post->title,
			'owner' => $post->user->username,
			'date' => date('M d, Y', strtotime($post->updated_at)),
			'content' => $Parsedown->text($post->content),
			'has_permission' =>$has_permission
		];
		$this->view('posts/show', $data);
    }
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit($id)
    {
		$this->_auth->_redirect_if_not_login();
		if(!is_numeric($id)){
			$this->redirect('/');
		}
		$user = User::where('username',$this->_auth->get_username())->first();
		$post = Post::where('id', $id)->first();
		if(empty($post)){
			$this->redirect('/');
		}
		$has_permission=($user->username==$post->user->username)?1:($user->is_admin==true)?1:0;
		if($has_permission!=1){
			if($user->is_admin==false){
				$data = [
					'login_user' => $this->_auth->get_username(),
					'id' => $post->id,
					'error' => 'You have no permission to modify this post.'
				];
				$this->view('posts/edit', $data);
			}
		}		
		$data = [
			'login_user' => $this->_auth->get_username(),
			'id' => $post->id,
			'title' => $post->title,
			'content' => $post->content,
		];
		$this->view('posts/edit', $data);
    }
	
	/**
     * Update the specified resource in storage.
     *
	 * @param  array  $post_param = $_POST
     * @param  int  $id
     *
     */
    public function update($post_params, $id)
    {
		$this->_auth->_redirect_if_not_login();
		if(!is_numeric($id)){
			$this->redirect('/');
		}
		$user = User::where('username',$this->_auth->get_username())->first();
		$post = Post::where('id', $id)->first();
		if(empty($post)){
			$this->redirect('/');
		}
		$has_permission=($user->username==$post->user->username)?1:($user->is_admin==true)?1:0;
		if($has_permission!=1){
			if($user->is_admin==false){
				$data = [
					'login_user' => $this->_auth->get_username(),
					'id' => $post->id,
					'error' => 'You have no permission to modify this post.'
				];
				$this->view('posts/edit', $data);
			}
		}
		
		$post->title = $post_params['title'];
		$post->content = $post_params['content'];
		$post->save();
		
		$this->redirect('/posts/'.$id);
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy($id)
    {
		
		$this->_auth->_redirect_if_not_login();
		if(!is_numeric($id)){
			$this->redirect('/');
		}
		$user = User::where('username',$this->_auth->get_username())->first();
		$post = Post::where('id', $id)->first();
		if(empty($post)){
			$this->redirect('/');
		}
		$has_permission=($user->username==$post->user->username)?1:($user->is_admin==true)?1:0;
        if($has_permission!=1){
			
			$this->redirect('/');
		}
		
		$post->delete();
		$this->redirect('/');
    }
}
