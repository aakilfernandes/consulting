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
		$validation = $isoform->validateInputs();
		if($validation->fails())
			return $isoform->redirect('/account');

		Auth::user()->fill(Input::all());
		Auth::user()->save();
		return Redirect::to('/account');
	}

	public function updatePassword()
	{

		$fieldNames = Isoform::getFieldNamesInNamespace('password');
		$validation = Isoform::validateInputs($fieldNames);	
		if($validation->fails())
			return Isoform::redirect(
				'password','/account',$fieldNames,$validation->messages('password')
			);


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

	public function checkout(){
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
