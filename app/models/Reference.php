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

	public static function matchingMessage(){

	}
}