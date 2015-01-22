@extends('layout')

@section('content')
<div class="container">
	<h1>Join</h1>
	<form {{Isoform::directive('join.email','join.name','join.company','join.password')}} method="post" action="">
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
					<input name="email" isoform-validate="join.email" class="form-control" type="email" required ng-model="email">
					<div isoform-messages="join.email">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td>
					<input name="name" class="form-control">
				</td>
			</tr>
			<tr>
				<td>Company</td>
				<td>
					<input name="company" class="form-control" type="company">
				</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>
					<input name="password" isoform-validate="join.password" class="form-control" type="password" ng-model="password">
					<div isoform-messages="join.password">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>Password <small class="text-muted">(confirm)</small></td>
				<td>
					<input name="password_confirmation" isoform-validate="join.password_confirmation" class="form-control" type="password" ng-model="password_confirmation">
					<div isoform-messages="join.password_confirmation">
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