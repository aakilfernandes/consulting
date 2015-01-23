<?php

class Reference extends \Eloquent {
	protected $fillable = [];

	public function __construct(){
		$this->saving(function($reference){
			$crawler = new Crawler();
			$crawler->addContent('<html><body>'.$reference->html.'</body></html>');
			
			$reference->hint = $crawler->filter('.hint')->text();
			$reference->description = $crawler->filter('.description')->html();
			return true;
		});
	}

	public function setHtmlAttribute($value){
		$this->attributes['html'] = removeLineBreaks($value);
	}

	public static function matchingMessage($message){

		if(stripos($message,'errors.angularjs.org')===-1)
			return null;

		$messageParts0 = explode(' ',$message)[0];
		$messageParts0 = str_ireplace('[','',$messageParts0);
		$messageParts0 = str_ireplace(']','',$messageParts0);
		$messageParts0Parts = explode(':',$messageParts0);
		array_unshift($messageParts0Parts,'error');

		$path = implode('/',$messageParts0Parts);

		$reference = Reference::where('path','=',$path)->first();

		if(!$reference)
			return null;
		else
			return $reference;
	}
}