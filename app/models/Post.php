<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent{
	/* uncomment to modify primary key */
	//protected $primaryKey = 'id';
	/* uncomment to remove 'created_at' and 'updated_at' */
	//public $timestamps = [];
	//uncomment to disable updated_at
	//public function setUpdatedAt($value){}
	protected $fillable = [
		'title',
		'content',
	];
	
	/**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}
