<?php

class Message extends \Eloquent {
	protected $fillable = ['name','company','email','hourlyMax','info'];
	protected $hidden = ['hourlyMax'];

	public function user(){
		return $this->belongsTo('User');
	}
}

Message::saving(function($message){
	$message->ip = Request::getClientIp();
});