<?php

class Project extends \Eloquent {
	protected $fillable = ['name','url','blurb'];

	public function user(){
		$this->belongsTo('User');
	}
}