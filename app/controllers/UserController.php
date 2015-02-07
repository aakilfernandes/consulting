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
			return $isoform->getRedirect('/account#user');

		Auth::user()->fill(Input::all());
		Auth::user()->save();
		return Redirect::to('/account#user');
	}

	public function updatePassword()
	{
		$isoform = new Isoform('password');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getRedirect('/account#password');

		Auth::user()->fill(Input::all());
		Auth::user()->save();
		return Redirect::to('/account');
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

	public function upgrade(){
		if(!Input::has('id'))
			App::error(400,'Missing id');

		Auth::user()->subscription('hacker')->create(Input::get('id'));
	}

	public function cancel(){
		if(!Auth::user()->isSubscribed)
			App::error(403,'You are not currently subscribed to any plans');

		Auth::user()->subscription()->cancel();
	}

	public function resume(){
		if(!Input::has('id'))
			App::error(400,'Missing id');

		if(!Auth::user()->isOnGracePeriod)
			App::error(403,'You are not currently on a grace period');

		Auth::user()->subscription(Auth::user()->stripe_plan)->resume(Input::get('id'));
	}
}

User::setStripeKey(getenv('STRIPE'));
