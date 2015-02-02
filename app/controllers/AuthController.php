<?php

class AuthController extends \BaseController {

	public function join(){
			$fieldNames = Isoform::getFieldNamesInNamespace('join');
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
		$fieldNames = Isoform::getFieldNamesInNamespace('login');
		$validation = Isoform::validateInputs($fieldNames);	
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
				'/login',$fieldNames,['password'=>['Password failed']]
			);

		return Redirect::to('/buckets');
	}

	public function reset(){
		$fieldNames = Isoform::getFieldNamesInNamespace('reset');
		$validation = Isoform::validateInputs($fieldNames);	
		if($validation->fails())
			return Isoform::redirect(
				'/reset',$fieldNames,$validation->messages()
			);

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
