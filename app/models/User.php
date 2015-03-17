<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = [
		'email'
		,'name'
		,'isEmailPublic'
		,'usesGravatar'
		,'title'
		,'hourlyMin'
		,'isAvailable'
		,'isRemote'
		,'country_id'
		,'city'
		,'state'
		,'zip'
		,'isNotifiedOfRequests'
		,'isNotifiedOfRequestsEvenIfLowball'
		,'password'
	];
	protected $hidden = ['hourlyMin','password', 'remember_token'];
	protected $appends = ['firstName'];
	protected $booleans = [
		'isAvailable'
		,'usesGravatar'
		,'isAvailable'
		,'isRemote'
		,'isNotifiedOfRequests'
		,'isNotifiedOfRequestsEvenIfLowball'
	];

	public function attributesToArray(){
		$attributes =  parent::attributesToArray();
		
		foreach ($attributes as $key=>$value)
			if(in_array($key, $this->booleans))
				$attributes[$key] = !! $value;

		return $attributes;
	}

	public function setAttribute($key, $value){
	    if(in_array($key, $this->booleans))
	        $this->attributes[$key] = !!$value;
		else
	        parent::setAttribute($key, $value);
	}

	public function getAttribute($key){
		if(in_array($key,$this->booleans))
			return !! parent::getAttribute($key);
		else
			return parent::getAttribute($key);
	}


	public function withRelationships(){
		return User::with('skills','projects')->find($this->id);
	}

	public function withHidden(){
		$this->hidden = [];
		return $this;
	}

	public function skills(){
		return $this->belongsToMany('Skill')->withPivot('level');
	}

	public function projects(){
		return $this->hasMany('Project');
	}

	public function messages(){
		return $this->hasMany('Message');
	}


	public function addSkill($attributes,$id=null){
		$skill = Skill::whereName($attributes['name'])->first();

		$pivotFields = [
			'level'=>$attributes['level']
		];

		if($id)
			$pivotFields['id']=$id;

		if($skill){
			$this->skills()->save($skill,$pivotFields);
			return;
		}

		$skill = new Skill;
		$skill->fill($attributes);
		$skill->save();

		$this->skills()->save($skill,$pivotFields);
	}

	public function getFirstNameAttribute(){
		return explode(' ',$this->name)[0];
	}

	public function getAvailabilityStringAttribute(){
		
		$string = $this->firstName;

		if(!$this->isAvailable)
			return $string.' is not available';
		else if($this->isRemote)
			return $string.' is available remotely';
		else
			return $string.' is available';
	}

	public function getTaglineAttribute(){
		if($this->title)
			$tagline = $this->title.' in ';
		else
			$tagline = 'Based in ';

		$tagline.=$this->city;

		if($this->state)
			$tagline.=', '.$this->state;

		return $tagline;
	}

	public function getProfileUrlAttribute(){
		return URL::to('/')."/p/{$this->id}/{$this->urlKey}/{$this->slug}";
	}

	public function getPublicPreviewUrlAttribute(){
		return $this->profileUrl.'?isPublicPreview=1';
	}

	public function getGravatarUrlAttribute(){
		return "http://www.gravatar.com/avatar/".md5($this->email).'?s=100';
	}

	public function getSlugAttribute(){
		return slugify($this->name);
	}

	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}

	public function setCountryIdAttribute($value){
		$this->attributes['country_id'] = 'US';
	}

	public function setHashedPasswordAttribute($value){
		$this->attributes['password']=$value;
	}

	public function checkPassword($password){
		return Hash::check($password,$this->password);
	}

}

User::creating(function($user){
	$user->urlKey = rand(1,10000);
});