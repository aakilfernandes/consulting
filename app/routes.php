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

Route::get('/', function(){
	return View::make('landing');	
});

Route::get('/logout', function(){
	Auth::logout();
	return Redirect::to('login');	
});

Route::group(['before'=>'guest'],function(){
	Route::get('/join', function(){
		return View::make('join');	
	});

	Route::get('/reset',function(){
		return View::make('reset');
	});

	Route::get('/login', function(){
		return View::make('login');	
	});
});


Route::get('/reset-complete', 'AuthController@resetComplete');

Route::get('/p/{id}/{urlKey}/{slug}', function($id){

	$isEditable = 
		Auth::user()
		&& Auth::user()->id == $id
		&& !Input::has('isPublicPreview');

	$user = User::find($id);

	return View::make('profile',[
		'user'=>$user->withRelationships()
		,'isEditable'=>$isEditable
	]);	
});

Route::get('/angular/templates/messageModal', function(){
	return View::make('messageModal');	
});

Route::group(['before'=>'csrf'],function(){
	Route::post('/login', 'AuthController@login');
	Route::post('/join', 'AuthController@join');
	Route::post('/reset', 'AuthController@reset');
});

Route::group(['before'=>'auth'],function(){

	Route::get('/profile',function(){
		return Redirect::to(Auth::user()->profileUrl);
	});

	Route::get('/messages',function(){
		return View::make('messages');
	});

	Route::get('/settings',function(){
		return View::make('settings');
	});

	Route::get('/angular/templates/skillModal', function(){
		return View::make('skillModal',[
			'skillsDatalist'=>
				File::get(base_path().'/data/skills.datalist')
		]);	
	});

	Route::get('/angular/templates/projectModal', function(){
		return View::make('projectModal');	
	});

	Route::get('/api/messages','MessagesController@index');
});

Route::group(['before'=>['csrf','auth']],function(){
	Route::post('/api/user/{id}/messages', 'UserController@sendMessage');
	Route::post('/api/skills', 'SkillsController@store');
	Route::post('/api/skills/{id}', 'SkillsController@update');
	Route::delete('/api/skills/{id}', 'SkillsController@destroy');
	Route::post('/api/projects', 'ProjectsController@store');
	Route::post('/api/projects/{id}', 'ProjectsController@update');
	Route::post('/api/projects/{id}/bump', 'ProjectsController@bump');
	Route::delete('/api/projects/{id}', 'ProjectsController@destroy');

	Route::post('/api/user','UserController@update');
});

Route::get('isoform', function(){
	return Isoform::ajaxValidationResponse();
});
