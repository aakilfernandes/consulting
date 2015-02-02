<?php

class Reset extends \Eloquent {
	protected $fillable = ['email','password'];

	public function __construct(){
		$this->token = randomToken();
	}

	public function setPasswordAttribute($value){
		$this->attributes['password'] = Hash::make($value);
	}

	public function getConfirmationUrlAttribute(){
		return URL::to('/reset-complete?'.http_build_query([
			'action'=>'confirm'
			,'token'=>$this->token
			,'email'=>$this->email
		]));
	}

	public function getRejectionUrlAttribute(){
		return URL::to('/reset-complete?'.http_build_query([
			'action'=>'reject'
			,'token'=>$this->token
			,'email'=>$this->email
		]));
	}

	public function sendEmail(){
		Email::messages()->send([
		    'subject' => "Confirm your requested password change"
		    ,'html' => "
		    	<p>Someone has requested a password change on your Angulytics account. Please select from one of the following options</p>
		    	<a href='{$this->confirmationUrl}'>I requested a password change</a>
		    	<br><a href='{$this->rejectionUrl}'>I did not request a password change</a>
		    "
		    ,'from_email' => 'aakil@angulytics.com'
		    ,'from_name' => 'Angulytics'
		    ,'to' => array(['email'=>$this->email])
			,'async'=>true    
		]);
	}
}