<?php

class UserController extends \BaseController {

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
	public function update()
	{
		$isoform = new Isoform('user');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getRedirect('/account#user')
				->with('growlMessages',[['error','User update failed']]);;

		Auth::user()->fill(Input::all());
		Auth::user()->save();
		return Redirect::to('/account#user')->with('growlMessages',[['success','Details updated']]);
	}

	public function updatePassword()
	{
		$isoform = new Isoform('password');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getRedirect('/account#password')
				->with('growlMessages',[['error','Password update failed']]);

		Auth::user()->fill(Input::all());
		Auth::user()->save();
		return Redirect::to('/account#password')->with('growlMessages',[['success','Password updated']]);
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

	public function sendMessage($id){
		
		$isoform = new Isoform('message');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getAjaxErrorResponse();

		$message = new Message;
		$message->fill(Input::all());

		User::find($id)->messages()->save($message);
	}
}

