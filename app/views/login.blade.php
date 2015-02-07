@extends('layout')

@section('content')
<div class="container">
	<h1>Login</h1>
	<p>
		Forgot your password? You can <a href="/reset">reset it here</a>.
	</p>
	<form isoform="{{Isoform::getSeed('login')}}" method="post" action="">
		@if(Session::has('isoformMessages'))
		<noscript><div class="alert alert-danger"><ul>
			@foreach(Session::get('isoformMessages') as $field=>$messages)
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
					<input name="email" isoform-field="email" class="form-control" type="email" required ng-model="email">
					<p class="text-danger" ng-repeat="message in isoform.messages.email" ng-cloak>
						@{{message}}
					</p>
				</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>
					<input name="password" isoform-field="password" class="form-control" type="password" ng-model="password">
					<p class="text-danger" ng-repeat="message in isoform.messages.password" ng-cloak>
						@{{message}}
					</p>
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