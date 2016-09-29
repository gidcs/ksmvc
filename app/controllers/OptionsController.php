<?php

/**
 *
 * you can use render method to display view when needed.
 * you can also pass variable to render via $data array. (optional)
 * 
 */

class OptionsController extends Controller
{
  /**
   *
   * Display a listing of the resource.
   *
   */
  public function index($id=1)
  {
    //
    $this->render('options/index', $data);
  }
  
  /** 
   *
   * Show the form for creating a new resource.
   *
   */  
  public function create()
  {   
    //
    $this->render('options/create', $data);  
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
    $this->render('options/show', $data);
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
    $this->render('options/edit', $data);
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
    //
  }
}