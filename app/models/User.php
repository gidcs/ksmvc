<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent{
	//uncomment to remove 'created_at' and 'updated_at'
	//public $timestamps = [];
	protected $fillable = ['username','password','email','is_admin'];
}
