<?php

class AuthController extends \BaseController {

	public function join(){
			$isoform = new Isoform('join');
			$validator = $isoform->getValidator(Input::all());

			if($validator->fails())
				return $isoform->getRedirect('/join');

			$user = new User;
			$user->fill(Input::all());
			$user->save();
			Auth::login($user);
			return Redirect::to('/buckets');
	}

	public function login(){
		$isoform = new Isoform('login');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getRedirect('/login');

		if(!Auth::attempt(
			[
				'email'=>Input::get('email')
				,'password'=>Input::get('password')
			]
		))
			return $isoform->getRedirect('/login',['password'=>['Password failed']]);

		return Redirect::to('/buckets');
	}

	public function reset(){
		$isoform = new Isoform('reset');
		$validator = $isoform->getValidator(Input::all());

		if($validator->fails())
			return $isoform->getRedirect('/reset');

		Reset::where('email',Input::get('email'))->delete();

		$reset = new Reset;
		$reset->fill(Input::all());
		$reset->save();
		$reset->sendEmail();

		$email = Input::get('email');
		return View::make('simple',[
			'title'=>'Reset confirmation sent'
			,'message'=>"Check your email at {$email} to confirm"
		]);
	}

	public function resetComplete(){
		$reset = 
			Reset::where('email',Input::get('email'))
			->where('token',Input::get('token'))
			->first();

		$user = User::where('email',Input::get('email'))->first();

		if(!$reset || !$user)
			return View::make('simple',[
				'title'=>'Bad token/email combination'
				,'message'=>"Something went wrong. Try to <a href='/reset'>reset</a> again."
			]);

		if(Input::get('action')=='reject'){
			$reset->delete();
			return View::make('simple',[
				'title'=>'Password reset rejected'
				,'message'=>"If you continue to receive password reset requests you did not initiate, please send an email to <a href='mailto:aakil@angulytics.com'>aakil@angulytics.com</a>"
			]);
		}

		$hoursLimit = 6;

		if($reset->created_at->diffInHours()>$hoursLimit)
			return View::make('simple',[
				'title'=>'Token expired'
				,'message'=>"That token is more than $hoursLimit hours old. Try to <a href='/reset'>reset</a> again."
			]);

		$user->hashedPassword = $reset->password;
		$user->save();

		$reset->delete();

		return View::make('simple',[
				'title'=>'Password reset complete'
				,'message'=>"Go ahead and <a href='/login'>log in</a>"
			]);

	}

}
