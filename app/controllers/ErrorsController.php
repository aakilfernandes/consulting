<?php

class ErrorsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($bucket_id,$profile_id)
	{
		$pageSize = Input::get('pageSize');
		$query = Auth::user()->buckets()->find($bucket_id)->profiles()->find($profile_id)->errors()->orderBy('created_at','DESC');

		if(Input::has('filters')){
			$filters = json_decode(Input::get('filters'));
			foreach($filters as $field => $value) 
				$query->where($field,'=',$value);
		}

		return $query->paginate($pageSize);
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
	public function store($version,$bucket_id)
	{
		if(!Input::has('dataJson'))
			return Response::json('Missing dataJson',403);

		$data = json_decode(Input::get('dataJson'),true);

		if(!$data)
			return Response::json('Unparsable dataJson',403);

		if(!$data['_key'])
			return Response::json('Missing key',403);

		$bucket = Bucket::find($bucket_id);
		
		if(!$bucket)
			return Response::json('Bucket not found',404);

		if($data['_key'] !== $bucket->key)
			return Response::json('Bad key',403);

		$error = new Error;
		$error->bucket_id = $bucket_id;
		$error->fill($data);
		$error->save();
		return $error;
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
	public function update($id)
	{
		//
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
