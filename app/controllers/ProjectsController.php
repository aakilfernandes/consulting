<?php

class ProjectsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		$isoform = new Isoform('project');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getAjaxErrorResponse();

		$project = new Project;
		$project->fill(Input::all());

		Auth::user()->projects()->save($project);

		return Auth::user()->projects;
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

		$isoform = new Isoform('project');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getAjaxErrorResponse();

		Auth::user()->projects()->find($id)->fill(Input::all())->save();
		return Auth::user()->projects;
	}

	public function bump($id){
		
		$project0 = Auth::user()->projects()->find($id);
		$order0 = $project0->order; 

		if(Input::get('direction')=='up')
			$order1 = $order0 - 1;
		else
			$order1 = $order0 + 1;

		$project1 = Auth::user()->projects()->whereOrder($order1)->first();

		$project0->order = $order1;
		$project1->order = $order0;

		$project0->save();
		$project1->save();

		return Auth::user()->projects;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Auth::user()->projects()->find($id)->delete();
	}


}
