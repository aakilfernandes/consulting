<?php

class Project extends \Eloquent {
	protected $fillable = ['name','role','url','blurb'];

	public function user(){
		$this->belongsTo('User');
	}
}