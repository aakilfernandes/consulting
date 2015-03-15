<?php

return [
	'login'=>[
		'email'=>[
			'required'=>[]
			,'email'=>[]
			,'exists'=>['users,email']
		],'password'=>[
			'required'=>[]
		]
	],'join'=>[
		'email'=>[
			'required'=>[]
			,'email'=>[]
			,'unique'=>['users,email']
		],'name'=>[
			'required'=>[]
		],'hourlyMin'=>[
			'required'=>[]
			,'integer'=>[]
			,'between'=>[100,500]
		],'country_id'=>[
			'required'=>[]
			//,'exists'=>['countries','id']
		],'zip'=>[
			'required'=>[]
		]
		,'password'=>[
			'required'=>[]
		],'passwordConfirmation'=>[
			'required'=>[]
			,'same'=>['password']
		]	
	],'reset'=>[
		'email'=>[
			'required'=>[]
			,'exists'=>['users,email']
		],'password'=>[
			'required'=>[]
		],'passwordConfirmation'=>[
			'required'=>[]
			,'same'=>['password']
		]
	],'user'=>[
		'email'=>[
			'required'=>[]
			,'email'=>[]
			,'mineOrUnique'=>['email']
		]
	],'password'=>[
		'password'=>[
			'required'=>[]
		]
		,'passwordConfirmation'=>[
			'required'=>[]
			,'same'=>['password']
		]
	],'skill'=>[
		'name'=>[
			'required'=>[]
		],'level'=>[
			'required'=>[]
		]
	],'project'=>[
		'name'=>[
			'required'=>[]
		],'url'=>[
			'url'=>[]
		]
		,'blurb'=>[]
	]
];