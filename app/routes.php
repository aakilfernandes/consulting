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

Route::get('/reset',function(){
	return View::make('reset');
});

Route::get('/reset-complete', 'AuthController@resetComplete');

Route::post('/endpoints/{version}/{id}','ErrorsController@store');

Route::group(['before'=>'csrf'],function(){
	Route::post('/login', 'AuthController@login');
	Route::post('/join', 'AuthController@join');
	Route::post('/reset', 'AuthController@reset');
});

Route::group(['before'=>'auth'],function(){
	Route::get('/buckets',function(){
		return View::make('buckets');
	});

	Route::get('/buckets/{id}/profiles',function($id){
		$bucket = Auth::user()->bucket($id);
		if(!$bucket) App::abort(404,'Bucket not found');
		return View::make('profiles',compact('bucket'));
	});

	Route::get('/buckets/{bucket_id}/profiles/{profile_id}',function($bucket_id,$profile_id){
		$bucket = Auth::user()->bucket($bucket_id);
		if(!$bucket) App::abort(404,'profile not found');

		$profile = $bucket->profiles()->find($profile_id);
		if(!$profile) App::abort(404,'profile not found');

		return View::make('profile',compact('bucket','profile'));
	});

	Route::get('/buckets/{bucket_id}/profiles/{profile_id}/referenceLink',function($bucket_id,$profile_id){
		$bucket = Auth::user()->bucket($bucket_id)->profiles()->find($profile_id);
		
		if(!$profile) App::abort(404,'Profile not found');
		if(!$profile->documentation) App::abort(404,'Profile has no documentation');

		return Redirect::to($profile->documentation);		
	});

	Route::get('/buckets/{bucket_id}/profile/{profile_id}/errors',function($bucket_id,$profile_id){
		$errors = Auth::user()->buckets()->find($bucket_id)->profiles()->find($profile_id)->errors;
		if(!$errors) App::abort(404,'Errors not found');
		return View::make('errors',compact('errors'));
	});

	Route::get('/api/buckets','BucketsController@index');
	Route::get('/api/buckets/{id}','BucketsController@show');
	Route::get('/api/buckets/{bucket_id}/profiles', 'ProfilesController@index');
	Route::get('/api/buckets/{bucket_id}/profiles/{profile_id}/errors', 'ErrorsController@index');

	Route::group(['before'=>'csrf'],function(){
		Route::post('/api/buckets', 'BucketsController@store');
		Route::put('/api/buckets/{id}', 'BucketsController@update');
		Route::delete('/api/buckets/{id}', 'BucketsController@destroy');
		
		Route::put('/api/buckets/{bucket_id}/profiles/{id}', 'ProfilesController@update');
		Route::put('/api/subscriptions/{id}', 'SubscriptionsController@update');
	});
});

Route::get('isoform', function()
{
	return Isoform::ajaxValidationResponse();
});
