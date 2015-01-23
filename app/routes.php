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

	Route::get('/buckets/{id}/profiles',function($id){
		$bucket = Auth::user()->buckets()->find($id);
		if(!$bucket) App::abort(404,'Bucket not found');
		return View::make('bucket-profiles',compact('bucket'));
	});

	Route::get('/buckets/{bucket_id}/profiles/{profile_id}/referenceLink',function($bucket_id,$profile_id){
		$profile = Auth::user()->buckets()->find($bucket_id)->profiles()->find($profile_id);
		
		if(!$profile) App::abort(404,'Profile not found');
		if(!$profile->documentation) App::abort(404,'Profile has no documentation');

		return Redirect::to($profile->documentation);		
	});

	Route::get('/buckets/{id}/errors',function($id){
		$bucket = Auth::user()->buckets()->find($id);
		if(!$bucket) App::abort(404,'Bucket not found');
		return View::make('bucket-errors',compact('bucket'));
	});




	Route::get('/api/buckets','BucketsController@index');
	Route::get('/api/buckets/{id}','BucketsController@show');
	Route::get('/api/buckets/{bucket_id}/profiles', 'ProfilesController@index');
	Route::get('/api/buckets/{bucket_id}/errors', 'ErrorsController@index');

	Route::group(['before'=>'csrf'],function(){
		Route::post('/api/buckets', 'BucketsController@store');
		Route::post('/api/buckets/{id}', 'BucketsController@update');
	});
});

Route::get('isoform', function()
{
	return Isoform::ajaxValidationResponse();
});
