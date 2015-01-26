<?php

class BucketsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /buckets
	 *
	 * @return Response
	 */
	public function index()
	{
		return Auth::user()->buckets;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /buckets/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /buckets
	 *
	 * @return Response
	 */
	public function store()
	{
		$bucket = new Bucket;
		$bucket->fill(Input::all());
		$bucket->save();
		Auth::user()->buckets()->save($bucket);
		return $bucket;
	}

	/**
	 * Display the specified resource.
	 * GET /buckets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$bucket = Auth::user()->bucket($id);
		if(!$bucket) return App::abort(404,'Bucket not found');
		return $bucket;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /buckets/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 * PUT /buckets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$bucket = Auth::user()->bucket($id);
		if(!$bucket) return App::abort(404,'Bucket not found');

		$bucket->fill(Input::all());
		$bucket->save();

		return $bucket;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /buckets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Auth::user()->bucket($id)->delete();
	}

}