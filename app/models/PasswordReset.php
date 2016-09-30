<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class PasswordReset extends Eloquent{
  //uncomment to remove 'created_at' and 'updated_at'
  //public $timestamps = [];
  //uncomment to disable updated_at
  public function setUpdatedAt($value){}
  protected $fillable = ['email','token'];
  
  /**
   * Get the user that owns the password_reset.
   */
  public function user()
  {
    return $this->belongsTo('User','email','email');
  }
}
