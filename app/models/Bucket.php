<?php

class Bucket extends \Eloquent {

	protected $fillable = ['name'];

	public function __construct(){
		$this->key = substr(str_shuffle(MD5(microtime())), 0, 24);
	}

	public function users(){
		return $this->belongsToMany('User');
	}

	public function profiles(){
		return $this->hasMany('Profile');
	}

	public function errors(){
		return $this->hasMany('Error');
	}

}