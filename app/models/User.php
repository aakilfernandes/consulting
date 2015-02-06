<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Laravel\Cashier\BillableTrait;
use Laravel\Cashier\BillableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface, BillableInterface {

	use UserTrait, RemindableTrait, BillableTrait;

	protected $fillable = ['email','name','company','password'];
	protected $hidden = ['password', 'remember_token'];
	protected $dates = ['trial_ends_at', 'subscription_ends_at'];

	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}

	public function setHashedPasswordAttribute($value){
		$this->attributes['password']=$value;
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
