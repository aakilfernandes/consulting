@extends('layout')

@section('content')
<div class="container" ng-controller="AccountController">
	@include('tabs',['tabId'=>'account'])
	<h1>Account</h1>
	<form id="user" ng-controller="UserController" isoform="{{Isoform::getSeed('user')}}" method="post" action="/user">
		<h3>Details</h3>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<table class="table">
			<tr>
				<td>Email</td>
				<td>
					<input name="email" isoform-field="email" class="form-control" type="email" required ng-model="user.email">
					<p class="text-danger" ng-repeat="message in isoform.messages.email" ng-cloak>
						@{{message}}
					</p>
					
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td>
					<input name="name" class="form-control" ng-model="user.name">
				</td>
			</tr>
			<tr>
				<td>Company</td>
				<td>
					<input name="company" class="form-control" type="company" ng-model="user.company">
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
	<form id="password" ng-controller="PasswordController" isoform="{{Isoform::getSeed('password')}}" method="post" action="/user/password">
		<h3>Password </h3>
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<table class="table">
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
	@if(Auth::user()->isSubscribed && !Auth::user()->isOnGracePeriod)
		<hr>
		<button class="btn btn-danger btn-xs" ng-click="cancel()">Cancel Subscription</button>
	@endif
</div>
@stop