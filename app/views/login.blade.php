@extends('layout')

@section('content')
<div class="container">
	<h1>Login</h1>
	<form {{Isoform::directive('login.email','login.password')}} method="post" action="">
		@if(Session::has('isoformMessages'))
		<noscript><div class="alert alert-danger"><ul>
			@foreach(Session::get('isoformMessages')->toArray() as $field=>$messages)
				@foreach($messages as $message)
					<li>{{$message}}</li>
				@endforeach
			@endforeach
		</ul></div></noscript>
		@endif
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<table class="table">
			<tr>
				<td>Email</td>
				<td>
					<input name="email" isoform-validate="login.email" class="form-control" type="email" required ng-model="email">
					<div isoform-messages="login.email">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>
					<input name="password" isoform-validate="login.password" class="form-control" type="password" ng-model="password">
					<div isoform-messages="login.password">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button class="btn btn-btn-primary">Submit</button>
				</td>
			</tr>
		</table>
	</form>
</div>
@stop