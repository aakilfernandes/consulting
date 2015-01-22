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

Route::get('/logout', function(){
	Auth::logout();
	return Redirect::to('login');	
});

Route::get('/join', function(){
	return View::make('join');	
});

Route::post('/endpoints/{id}','ErrorsController@store');

Route::group(['before'=>'csrf'],function(){
	Route::post('/login', 'AuthController@login');
	Route::post('/join', 'AuthController@join');
});

Route::group(['before'=>'auth'],function(){
	Route::get('/buckets',function(){
		return View::make('buckets');
	});

	Route::get('/buckets/{id}/{slug}',function($id,$slug){

		$bucket = Bucket::find($id);

		if(!$bucket) App::abort(404,'Bucket not found');

		return View::make('bucket',compact('bucket'));
	});


	Route::get('/api/buckets','BucketsController@index');
	Route::get('/api/buckets/{id}','BucketsController@show');

	Route::group(['before'=>'csrf'],function(){
		Route::post('/api/buckets', 'BucketsController@store');
		Route::post('/api/buckets/{id}', 'BucketsController@update');
	});
});

Route::get('isoform', function()
{
	return Isoform::ajaxValidationResponse();
});
