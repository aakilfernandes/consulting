<?php

class Error extends \Eloquent {
	protected $fillable = ['message','name','url','useragent','stack'];

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
		$this->summary = createSummary($value);
	}

	public function getStackAttribute(){
		return json_decode($this->attributes['stack']);
	}

	public function createSummary($stack = null){

		$summaryParts = [$this->name];

		if(!$stack) $stack = $this->stack;

		$firstLine = $stack[0];
		$urlParts = explode('\\', $firstLine->url);
		$summaryParts[] = end($urlParts);
		$summaryParts[] = $firstLine->line;
		$summaryParts[] = $firstLine->column;
		
		return implode(':',$summaryParts);
	}
}