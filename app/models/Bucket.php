<?php

class Bucket extends \Eloquent {

	protected $fillable = ['name'];
	protected $appends = ['slug'];

	public function __construct(){
		$this->key = substr(str_shuffle(MD5(microtime())), 0, 24);
	}

	public function users(){
		return $this->belongsToMany('User');
	}

	public function getSlugAttribute(){
		return slugify($this->name);
	}

}