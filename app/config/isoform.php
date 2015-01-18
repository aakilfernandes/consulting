<?php

return [
	'login.email'=>[
		'required'=>[]
		,'email'=>[]
		,'exists'=>['users,email']
	],'login.password'=>[
		'required'=>[]
	]
];