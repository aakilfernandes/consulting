@extends('layout')

@section('content')
<div class="container">
	<h1>Reset Password</h1>
	<p>Enter your email and your desired new password. We'll send a validation email to your account.</p>
	<form isoform="{{Isoform::getSeed('reset')}}" method="post" action="">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<input type="hidden" name="_isoformNamespace" value="reset">
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
				<td>New Password</td>
				<td>
					<input name="password" isoform-field="password" class="form-control" type="password" required ng-model="password">
					<p class="text-danger" ng-repeat="message in isoform.messages.password" ng-cloak>
						@{{message}}
					</p>
				</td>
			</tr>
			<tr>
				<td>New Password (confirm)</td>
				<td>
					<input name="password_confirmation" isoform-field="password_confirmation" class="form-control" type="password" required ng-model="password_confirmation">
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