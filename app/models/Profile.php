<?php

class Profile extends \Eloquent {
	protected $fillable = ['bucket_id','summary','message','name'];

	protected $appends = ['errorsCount','lastError','clients','alias'];

	public function __construct(){

		$this->saving(function($profile){
			$profile->reference_id = $profile->determineReferenceId();
			return true;
		});
	}

	public function bucket(){
		return $this->belongsTo('Bucket');
	}

	public function errors(){
		return $this->hasMany('Error');
	}

	public function reference(){
		return $this->belongsTo('Reference');
	}

	public function getAliasAttribute(){
		if($this->attributes['alias'])
			return $this->attributes['alias'];
		
		if($this->reference)
			return 'Angular: '.$this->reference->hint;

		return $this->name.': '.$this->message;
	}

	public function getErrorsCountAttribute(){
		return $this->errors()->count();
	}

	public function getLastErrorAttribute(){
		return $this->errors()->orderBy('created_at','DESC')->first();
	}

	public function getClientsAttribute(){
		return $this->errors()->groupBy('browser','os','device')->get();
	}

	public function determineReferenceId(){

		$reference = Reference::matchingMessage($this->message);

		if(!$reference)
			return null;
		else
			return $reference->id;

	}

}