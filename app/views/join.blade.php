@extends('layout')

@section('content')
<textarea frontload="countries" frontload-type="json">{{Country::orderBy('name','ASC')->get()}}</textarea>
<div class="container" ng-controller="JoinController">
	<h1>Join</h1>
	<p>The on-boarding process should take about 5 minutes. If you're too busy to do it now, I'd be happy to <a>text you a reminder</a> in a few days.</p>
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
		<h3>Basics</h3>
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
				</td>
			</tr>
			<tr>
				<td>
					Tagline <small class="text-muted">(optional)</small>
				</td>
				<td>
					<input name="tagline" class="form-control" ng-model="tagline" placeholder="Frontend Developer">
					{{getHtmlForIsoformMessages('tagline')}}
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
			<tr>
				<td>Password</td>
				<td>
					<input name="password" class="form-control" type="password" ng-model="password">
					{{getHtmlForIsoformMessages('password')}}
				</td>
			</tr>
			<tr>
				<td>Password <small class="text-muted">(confirm)</small></td>
				<td>
					<input name="passwordConfirmation" class="form-control" type="password" ng-model="passwordConfirmation">
					{{getHtmlForIsoformMessages('passwordConfirmation')}}
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