<?php

class Bucket extends \Eloquent {

	protected $fillable = ['name'];
	protected $appends = ['openProfilesCount'];

	public function __construct(){
		$this->key = substr(str_shuffle(MD5(microtime())), 0, 24);
	}

	public function users(){
		return $this->belongsToMany('User');
	}

	public function profiles(){
		return $this->hasMany('Profile');
	}

	public function errors(){
		return $this->hasMany('Error');
	}

	public function getErrorsFiltersOptionsAttribute(){
		$filtersOptions = new stdClass;
		//TODO: get profile options in less expensive way
		$filtersOptions->profiles = $this->profiles;
		foreach($filtersOptions->profiles as $key=>$profile){
			$filtersOptions->profiles[$key]->attributes['lastError'] = null;
			$filtersOptions->profiles[$key]->attributes['stack'] = null;
		}

		$filtersOptions->browsers = $this-> optionsForField('browser');
		$filtersOptions->oses = $this-> optionsForField('os');
		$filtersOptions->devices = $this-> optionsForField('device');
		return $filtersOptions;
	}

	public function optionsForField($field){
		return DB::table('errors')
			->where('bucket_id','=',$this->id)
			->select(DB::raw("$field as value"))
			->groupBy($field)
			->get();
	}

	public function getOpenProfilesCountAttribute(){
		return $this->profiles()->where('status_id','=','default')->count();
	}

}