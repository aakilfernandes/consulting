<?php

class SkillsController extends \BaseController {

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
		$isoform = new Isoform('skill');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getAjaxErrorResponse();

		if(Auth::user()->skills()->whereName(Input::get('name'))->count())
			return $isoform->getCustomErrorResponse([
				'name'=>["You've already added that skill"]
			]);

		Auth::user()->addSkill(Input::all());

		return Auth::user()->skills;
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
		$isoform = new Isoform('skill');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getAjaxErrorResponse();

		Auth::user()->skills()->detach($id);
	Auth::user()->addSkill(Input::all(),$id);

		return Auth::user()->skills;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Auth::user()->skills()->wherePivot('skill_id',$id)->delete();
	}


}
