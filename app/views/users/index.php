<?php
  $this->includes("layouts/header",$data);
  $this->includes("layouts/alerts",$data);
?>

<div class="container"> 
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="panel panel-default"> 
        <div class="panel-heading clearfix">
          <div class="panel-title pull-left">Users</div>
          <div class="pull-right">
            <a href="<?=Route::URI('UsersController#create')?>" class="btn btn-default">
              <span class="glyphicon glyphicon-plus"></span>
            </a> 
          </div>
        </div>
        <table class="table table-bordered table-center">
          <thead>
          <tr>
            <th class="col-xs-3 col-sm-3">Username</th>
            <th class="hidden-xs col-sm-3">Email</th>
            <th class="col-xs-3 col-sm-2">Role</th>
            <th class="col-xs-6 col-sm-3">Action</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($data['users'] as $u){ ?>
            <tr>
              <td class="col-xs-3 col-sm-3">
                <?=$u->username?>
              </td>
              <td class="hidden-xs col-sm-3">
                <?=$u->email?>
              </td>
              <td class="col-xs-3 col-sm-3">
                <?=Role::find_role_name($u->role)?>
              </td>
              <td class="col-xs-6 col-sm-3">
                <form role="form" method="POST" 
                  action="<?=Route::URI('UsersController#destroy')?><?=$u->id?>">
                  <?=Route::Method('DELETE')?>
                  <a href="<?=Route::URI('UsersController#edit')?><?=$u->id?>/edit" class="btn btn-primary"
                    data-toggle="tooltip" title="Edit">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                  </a>
                  <button type="submit" class="btn btn-danger"
                    data-toggle="tooltip" title="Delete">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                  </button>
                </form>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <div class="panel-footer text-center">
          <div class="btn-group">
            <a href="<?=Route::URI('UsersController#index')?>" class="btn btn-default <?php if($data['page_id']==1) echo "disabled";?>">
              <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
            </a>
            <a href="<?=Route::URI('UsersController#index')."/page/".($data['page_id']-1)?>" class="btn btn-default <?php if($data['page_id']==1) echo "disabled";?>">
              <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
            </a>
            <a href="<?=Route::URI('UsersController#index')."/page/".($data['page_id']+1)?>" class="btn btn-default <?php if($data['page_id']+1>$data['max_id']) echo "disabled";?>">
              <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
            </a>
            <a href="<?=Route::URI('UsersController#index')."/page/".$data['max_id']?>" class="btn btn-default <?php if($data['page_id']==$data['max_id']) echo "disabled";?>">
              <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  $this->includes("layouts/footer");
?>
