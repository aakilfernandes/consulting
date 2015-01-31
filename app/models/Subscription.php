<?php

class Subscription extends \Eloquent {
	protected $fillable = ['profileCreated','profileReopened'];

	protected $appends = ['profileCreated','profileReopened'];

	public function getProfileCreatedAttribute(){
		return (boolean) $this->attributes['profileCreated'];
	}

	public function getProfileReopenedAttribute(){
		return (boolean) $this->attributes['profileReopened'];
	}
}