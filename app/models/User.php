<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = ['email','name','company','password'];
	protected $hidden = ['password', 'remember_token'];

	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}

	public function checkPassword($password){
	return Hash::check($password,$this->password);
	}

	public function buckets(){
		return $this->belongsToMany('Bucket');
	}

	public function bucket($id){
		return $this->buckets()->whereBucketId($id)->first();
	}

}
