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
		,'title'
		,'hourlyMin'
		,'isAvailable'
		,'isRemote'
		,'country_id'
		,'zip'
		,'isNotifiedOfRequests'
		,'isNotifiedOfRequestsEvenIfLowball'
		,'password'
	];
	protected $hidden = ['password', 'remember_token'];
	protected $appends = ['firstName'];

	public function withRelationships(){
		return User::with('skills','projects')->find($this->id);
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

	public function setIsEmailPublicAttribute($value){
		$this->attributes['isEmailPublic'] = !!$value;
	}

	public function setIsAvailableAttribute($value){
		$this->attributes['isAvailable'] = !!$value;
	}

	public function setIsRemoteAttribute($value){
		$this->attributes['isRemote'] = !!$value;
	}

	public function setIsNotifiedOfRequestsAttribute($value){
		$this->attributes['isNotifiedOfRequests'] = !!$value;
	}

	public function setIsNotifiedOfRequestsEvenIfLowballAttribute($value){
		$this->attributes['isNotifiedOfRequestsEvenIfLowball'] = !!$value;
	}

}

User::saving(function($user){
	$user->urlKey = rand(1,10000);
});