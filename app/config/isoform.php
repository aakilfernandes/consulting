<?php

return [
	'login.email'=>[
		'required'=>[]
		,'email'=>[]
		,'exists'=>['users,email']
	],'login.password'=>[
		'required'=>[]
	],'join.email'=>[
		'required'=>[]
		,'email'=>[]
		,'unique'=>['users,email']
	],'join.password'=>[
		'required'=>[]
	],'join.password_confirmation'=>[
		'required'=>[]
		,'same'=>['join.password']
	]
];