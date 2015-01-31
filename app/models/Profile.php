<?php

class Profile extends \Eloquent {
	protected $fillable = ['bucket_id','summary','message','name','status'];

	protected $appends = ['errorsCount','lastError','alias','documentationLink','clients'];


	public static function boot()
    {
        parent::boot();

        Profile::creating(function($profile){
			$profile->reference_id = $profile->determineReferenceId();
			return true;
		});

		Profile::created(function($profile){
			//$profile->sendEmails('profileCreated');
			return true;
		});

		Profile::updating(function($profile){
			if($profile->status == 'open' && $profile->getOriginal()['status'] == 'closed')
				$profile->sendEmails('profileReopened');

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
		if(isset($this->attributes['alias']))
			return $this->attributes['alias'];
		
		if($this->reference){
			$aliasParts = ['Angular:',$this->reference->hint];
			if($this->argumentsString)
				$aliasParts[] = $this->argumentsString;
			return implode(' ',$aliasParts);
		}

		return $this->name.': '.$this->message;
	}

	public function getUrlAttribute(){
		return URL::to("/buckets/{$this->bucket_id}/profiles/{$this->id}");
	}

	public function sendEmails($type){
		$this->flushEventListeners();
		$profile=$this;
		$subscriptions = $this->bucket->subscriptions()->where($type,1)->get();

		switch($type){
			case 'profileCreated':
				$tag = 'New';
				break;
			case 'profileReopened':
				$tag = 'Re-Opened';
				break;
		}

		foreach($subscriptions as $subscription)
			Email::messages()->send([
			    'subject' => "[$tag] {$profile->alias}"
			    ,'html' => View::make('emails.profile',compact('profile','tag'))->render()
			    ,'from_email' => 'aakil@angulytics.com'
			    ,'from_name' => 'Angulytics'
			    ,'to' => array(['email'=>$subscription->user->email])
				,'async'=>true    
			]);
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
		
		$stripos = stripos($this->message,'errors.angularjs.org');
		return 'http://'.substr($this->message,$stripos);
	}

	public function determineReferenceId(){

		$reference = Reference::matchingMessage($this->message);

		if(!$reference)
			return null;
		else
			return $reference->id;

	}

}