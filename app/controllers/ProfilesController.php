<?php

class ProfilesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($bucket_id)
	{
		$query = Auth::user()->buckets()->find($bucket_id)->profiles();

		$filters = json_decode(Input::get('filtersJson'));

		foreach($filters as $field => $value){
			if(is_array($value))
				$query->whereIn($field,$value);
			else
				$query->where($field,'=',$value);
		}

		switch(Input::get('sort')){
			case 'highestPriority':
				return $query
					->join('statuses', 'status_id', '=', 'statuses.id')
					->orderBy('statuses.priority','DESC')
					->get(['profiles.*','statuses.priority']);
				break;
			case 'recentlySeen':
				return $query
					->join('errors', 'profiles.id', '=', 'errors.profile_id')
					->orderBy('recentlySeen','DESC')
					->groupBy('errors.profile_id')
					->get(['profiles.*',DB::raw('MAX(errors.created_at) as recentlySeen')]);
		}

		return $query->orderBy('created_at','DESC')->get();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($bucket_id,$id)
	{
		$profile = Auth::user()->buckets()->find($bucket_id)->profiles()->find($id);
		if(!$profile) App::error(404,'Profile not found');

		$profile->fill(Input::all());
		$profile->save();
		return $profile;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
