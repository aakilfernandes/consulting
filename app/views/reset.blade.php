@extends('layout')

@section('content')
<div class="container">
	<h1>Reset Password</h1>
	<p>Enter your email and your desired new password. We'll send a validation email to your account.</p>
	<form {{Isoform::directive('reset',['email','password','password_confirmation'])}} method="post" action="">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<input type="hidden" name="_isoformNamespace" value="reset">
		<table class="table">
			<tr>
				<td>Email</td>
				<td>
					<input name="email" isoform-validate="email" class="form-control" type="email" required ng-model="email">
					<div isoform-messages="email">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>New Password</td>
				<td>
					<input name="password" isoform-validate="password" class="form-control" type="password" required ng-model="password">
					<div isoform-messages="password">
						<p class="text-danger" ng-repeat="message in isoformMessages" ng-cloak>
							@{{message}}
						</p>
					</div>
				</td>
			</tr>
			<tr>
				<td>New Password (confirm)</td>
				<td>
					<input name="password_confirmation" isoform-validate="password_confirmation" class="form-control" type="password" required ng-model="password_confirmation">
					<div isoform-messages="password_confirmation">
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