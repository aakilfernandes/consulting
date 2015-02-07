@extends('layout')

@section('content')
<div class="container">
	<h1>Join</h1>
	<form isoform="{{Isoform::getSeed('join')}}" method="post" action="">
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
					<input name="email" isoform-field="email" class="form-control" type="email" required ng-model="email">
					<p class="text-danger" ng-repeat="message in isoform.messages.email" ng-cloak>
						@{{message}}
					</p>
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td>
					<input name="name" class="form-control" ng-model="name" isoform-field="name">
				</td>
			</tr>
			<tr>
				<td>Company</td>
				<td>
					<input name="company" class="form-control" type="company" ng-model="company" isoform-field="company">
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
				<td>Password <small class="text-muted">(confirm)</small></td>
				<td>
					<input name="password_confirmation" isoform-field="password_confirmation" class="form-control" type="password" ng-model="password_confirmation">
					<p class="text-danger" ng-repeat="message in isoform.messages.password_confirmation" ng-cloak>
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