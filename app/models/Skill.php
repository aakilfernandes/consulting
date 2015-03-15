<?php

class Skill extends \Eloquent {
	
	public $timestamps = false;
	protected $fillable = ['name'];

	public function users(){
		return $this->belongsToMany('User')->withPivot('level');
	}

	public function getLevelAttribute(){
		return $this->pivot->attributes;
	}
}