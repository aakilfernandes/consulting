<?php

class Profile extends \Eloquent {
	protected $fillable = ['bucket_id','summary','message','name'];

	protected $appends = ['errorsCount','lastError','clients'];

	public function __construct(){

		$this->saving(function($profile){
			if(stripos($profile->message,'errors.angularjs.org')===-1)
				return true;
			
			$reference = Reference::matchingMessage($profile->message);

			if(!$reference)
				return true;

			$this->reference_id = $reference->id;
			return true;
		});
	}

	public function bucket(){
		return $this->belongsTo('Bucket');
	}

	public function errors(){
		return $this->hasMany('Error');
	}

	public function getAliasAttribute(){
		if($this->attributes['alias'])
			return $this->attributes['alias'];
		
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

}