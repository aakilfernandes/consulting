<?php

class AuthController extends \BaseController {

	public function join(){
			$fieldNames = ['join.email','join.password','join.password_confirmation'];
			$validation = Isoform::validateInputs($fieldNames);	
			if($validation->fails())
				return Isoform::redirect(
					'/join',$fieldNames,$validation->messages()
				);

			$user = new User;
			$user->fill(Input::all());
			$user->save();
			Auth::login($user);
			return Redirect::to('/buckets');
	}

	public function login(){
		$fieldNames = ['login.email','login.password'];
		$validation = Isoform::validateInputs(['login.email','login.password']);	
		if($validation->fails())
			return Isoform::redirect(
				'/login',$fieldNames,$validation->messages()
			);

		if(!Auth::attempt(
			[
				'email'=>Input::get('email')
				,'password'=>Input::get('password')
			]
		))
			return Isoform::redirect(
				'/login',$fieldNames,['login.password'=>['Password failed']]
			);

		return Redirect::to('/buckets');
	}

}
