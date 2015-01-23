<?php

class Profile extends \Eloquent {
	protected $fillable = ['bucket_id','summary','message','name'];

	protected $appends = ['errorsCount','lastError','alias','documentationLink','clients'];

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
		
		if($this->reference){
			$aliasParts = ['Angular:',$this->reference->hint];
			if($this->argumentsString)
				$aliasParts[] = $this->argumentsString;
			return implode(' ',$aliasParts);
		}

		return $this->name.': '.$this->message;
	}

	public function getArgumentsStringAttribute(){
		$parsedDocumentationUrl = parse_url($this->documentationLink);
		
		if(!isset($parsedDocumentationUrl['query']))
			return null;
		
		$params = [];
		$fragmentParts = parse_str($parsedDocumentationUrl['query'],$params);

		$arguments = array_map(function($value){
			return $value;
		}, $params);

		return '('.implode(',',$params).')';
	}

	public function getErrorsCountAttribute(){
		return $this->errors()->count();
	}

	public function getLastErrorAttribute(){
		return $this->errors()->orderBy('created_at','DESC')->first();
	}

	public function getClientsAttribute(){
		return DB::table('errors')
			->select('browser','os','device',DB::raw('count(*) as errorsCount'))
			->groupBy('browser','os','device')
			->where('profile_id','=',$this->id)
			->get();
	}

	public function getDocumentationLinkAttribute(){

		if(stripos($this->message,'errors.angularjs.org') === false)
			return null;
		
		return explode(' ',$this->message)[1];

	}

	public function determineReferenceId(){

		$reference = Reference::matchingMessage($this->message);

		if(!$reference)
			return null;
		else
			return $reference->id;

	}

}