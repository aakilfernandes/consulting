<?php

class Error extends \Eloquent {
	protected $fillable = ['message','name','url','useragent','stack'];
	protected $appends = ['stack'];

	public static function boot(){
        parent::boot();

		Error::saving(function($error){

			$parsedUseragent = $error->parseUseragent();
			$error->browser = $parsedUseragent->ua->family;
			$error->os = $parsedUseragent->os->family;
			$error->device = $parsedUseragent->device->family;

			$error->summary = $error->determineSummary();
			
			$profile = $error->determineProfile();
			$error->profile_id = $profile->id;

			if($profile->status=='closed'){
				$profile->status = 'open';
				$profile->save();
			}

			$parsedUrl = parse_url($error->url);
			foreach([
				'host'=>'urlHost'
				,'path'=>'urlPath'
				,'query'=>'urlQuery'
				,'fragment'=>'urlFragment'
			] as $part => $field){
				if(!isset($parsedUrl[$part])) continue;
				$error->{$field} = $parsedUrl[$part];
			}

			return true;
		});
	}

	public function bucket(){
		return $this->belongsTo('Bucket');
	}

	public function setStackAttribute($value){

		$valueJson = json_encode($value);
		$round = 0;

		while(strlen($valueJson)>=16777215){

			if($round%2==0)
				$value = array_slice($value, 0, -1);
			else
				$value = array_slice($value, 1);

			$valueJson = json_encode($value);
			$round++;
		};


		$this->attributes['stack'] = $valueJson; 
	}

	public function getStackAttribute(){
		return json_decode($this->attributes['stack']);
	}

	public function determineSummary(){

		$summaryParts = [];

		$firstLine = $this->stack[0];
		$urlParts = explode('/', $firstLine->url);
		$summaryParts[] = end($urlParts);

		$summaryParts[] = $firstLine->line;
		$summaryParts[] = $firstLine->column;
		$summaryParts[] = $this->message;

		return json_encode($summaryParts);
	}

	public function parseUseragent(){
		$parser = UseragentParser::create();
		return $parser->parse($this->useragent);
	}

	public function determineProfile(){
		
		$profile = $this->bucket->profiles()->where('summary','=',$this->summary)->first();
		
		if($profile) return $profile;

		$profile = new Profile;
		$profile->fill($this->attributes);
		$profile->save();

		return $profile;
	}

}