<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Option extends Eloquent{
  /* uncomment to modify primary key */
  //protected $primaryKey = 'id';
  /* uncomment to remove 'created_at' and 'updated_at' */
  //public $timestamps = [];
  //uncomment to disable updated_at
  //public function setUpdatedAt($value){}
  protected $fillable = ['name', 'value'];
}
