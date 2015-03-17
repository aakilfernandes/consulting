<?php

class Project extends \Eloquent {
	protected $fillable = ['name','role','url','blurb'];

	public function user(){
		$this->belongsTo('User');
	}
}

Project::creating(function($project){
	$project->order = Auth::user()->projects()->count();
});