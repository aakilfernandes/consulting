<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/login', function(){
	return View::make('login');	
});

Route::group(['before'=>'csrf'],function(){
	Route::post('/login', function(){
		$validation = Isoform::validateInputs(['login.email','login.password']);	
		if($validation->fails())
			return Isoform::redirect(
				'/login'
				,['login.email','login.password']
				,$validation->messages()
			);
		return Redirect::to('app');
	});
});


Route::get('isoform', function()
{
	return Isoform::ajaxValidationResponse();
});
