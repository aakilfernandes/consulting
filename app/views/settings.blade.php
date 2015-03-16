@extends('layout')

@section('content')
<textarea frontload="countries" frontload-type="json">{{Country::all()}}</textarea>
<div class="container">

<h4>Info</h4>
<form isoform="{{Isoform::getSeed('user')}}" ng-submit="update($event)" ng-controller="UserController">
<table class="table">
	<table class="table">
		<tr>
			<td>Name</td>
			<td>
				<input name="name" class="form-control" ng-model="name" placeholder="John Doe">
				{{getHtmlForIsoformMessages('name')}}
			</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>
				<input name="email" class="form-control"  ng-model="email" placeholder="john.doe@gmail.com">
				{{getHtmlForIsoformMessages('email')}}
				<hr class="hr-small">
				<label>
					<input type='hidden' value='' name='isEmailPublic'>
					<input type="checkbox" name="isEmailPublic" ng-model="isEmailPublic">
					Show my email on my profile
				</label>
				<label>
					<input type='hidden' value='' name='usesGravatar'>
					<input type="checkbox" name="usesGravatar" ng-model="usesGravatar">
					Use the gravatar associated with this email
				</label>
			</td>
		</tr>
		<tr>
			<td>
				Title <small class="text-muted">(optional)</small>
			</td>
			<td>
				<input name="title" class="form-control" ng-model="title" placeholder="Frontend Developer">
				{{getHtmlForIsoformMessages('title')}}
			</td>
		</tr>
		<tr>
			<td>
				Minimum Hourly Rate <small class="text-muted">(this won't be made public)</small>
			</td>
			<td>
				<div class="input-group">
				  <span class="input-group-addon">$</span>
				  <input name="hourlyMin" class="form-control" ng-model="hourlyMin">
				  <span class="input-group-addon">.00</span>
				</div>
				{{getHtmlForIsoformMessages('hourlyMin')}}
			</td>
		</tr>
		<tr>
			<td>
				Availability
			</td>
			<td>
				<label>
					<input type='hidden' name='isAvailable' value="off">
					<input type="checkbox" name="isAvailable" ng-model="isAvailable">
					I am currently available for new projects
				</label>
				<label>
					<input type='hidden' name='isRemote' value="off">
					<input type="checkbox" name="isRemote" ng-model="isRemote">
					I am only interested in remote jobs
				</label>
			</td>				
		</tr>
		<tr>
			<td>Notifications</td>
			<td>
				<label>
					<input type='hidden' name='isNotifiedOfRequests' value="off">
					<input type="checkbox" name="isNotifiedOfRequests" ng-model="isNotifiedOfRequests">
					Email me when someone sends me a request
				</label>
				<label>
					<input type='hidden' name='isNotifiedOfRequestsEvenIfLowball' value="off">
					<input type="checkbox" name="isNotifiedOfRequestsEvenIfLowball" ng-model="isNotifiedOfRequestsEvenIfLowball">
					Email me even if their maximum hourly rate is below my minimum hourly rate
				</label>
			</td>
		</tr>
		<tr>
			<td>Country/Region</td>
			<td>
				<select class="form-control" ng-options="country.id as country.name for country in frontloaded.countries track by country.id" ng-model="country_id" name="country_id">
				</select>
				{{getHtmlForIsoformMessages('country_id')}}
			</td>				
		</tr>
		<tr>
			<td>Zip/Postal Code</td>
			<td>
				<input name="zip" class="form-control" ng-model="zip">
				{{getHtmlForIsoformMessages('zip')}}
			</td>
		</tr>
	<tr><td></td><td><button class="btn btn-primary">Submit</button></td></tr>
</table></form>
<hr>
<h4>New Password</h4>
<form isoform="{{Isoform::getSeed('password')}}" ng-submit="update($event)" ng-controller="PasswordController">
<table class="table">
	<tr>
		<td style="width:50%">New Password</td>
		<td>
			<input type="password" class="form-control" name="password" ng-model="password" required>
			{{getHtmlForIsoformMessages('password')}}
		</td>
	</tr>
	<tr>
		<td>New Password Confirmation</td>
		<td>
			<input type="password" class="form-control" name="passwordConfirmation" ng-model="passwordConfirmation" required>
			{{getHtmlForIsoformMessages('passwordConfirmation')}}
		</td>
	</tr>
	<tr><td></td><td><button class="btn btn-primary">Submit</button></td></tr>
</table></form>
@stop