@extends('layout')

@section('content')
	<textarea frontload="bucket_id" frontload-type="integer">{{$bucket->id}}</textarea>
	<textarea frontload="errorsFiltersOptions" frontload-type="json">{{ json_encode($bucket->errorsFiltersOptions)}}</textarea>
	<div class="container">
		<h1>{{$bucket->name}} Errors</h1>
		<div ng-controller="ErrorsController">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<h4 class="hidden-xs hidden-sm">Profiles</h4>
					<select ng-model="errorsFilters.profile_id" class="form-control"
						ng-options="profile.id as profile.value for profile in errorsFiltersOptions.profiles"
					></select>
					<label><input type="checkbox"></input> Active Profiles Only</label>
				</div>
				<div class="col-xs-6 col-sm-3">
					<h4 class="hidden-xs hidden-sm">Profiles</h4>
					<select ng-model="errorsFilters.browser" class="form-control"
						ng-options="browser.id as browser.value for browser in errorsFiltersOptions.browsers"
					></select>
				</div>
				<div class="col-xs-6 col-sm-3">
					<h4 class="hidden-xs hidden-sm">Operating Systems</h4>
					<select ng-model="errorsFilters.os" class="form-control"
						ng-options="os.id as os.value for os in errorsFiltersOptions.oses"
					></select>
				</div>
				<div class="col-xs-6 col-sm-3">
					<h4 class="hidden-xs hidden-sm">Devices</h4>
					<select ng-model="errorsFilters.device" class="form-control"
						ng-options="device.id as device.value for device in errorsFiltersOptions.devices"
					></select>
				</div>
			</div>
			<hr>
			<div class="text-center">
				<div pagination  class="" ng-cloak
					total-items="errorsCount"
					max-size="10"
					ng-model="params.page"
					previous-text="Prev"
					boundary-links="true"
				></div>
			</div>
			<div class="panel panel-default" ng-repeat="error in errors" ng-cloak>
				<div class="panel-body">
					<table class="table">
						<tr>
							<td>Time</td>
							<td timestamp="error.created_at"></td>
						</tr>
						<tr>
							<td>Explore</td>
							<td>
								<button class="btn btn-primary" show-stack="error.stack">Show Stack</button>
							</td>
						</tr>
						<tr>
							<td>Client</td>
							<td>
								Browser: @{{error.browser}}
								<br>OS: @{{error.os}}
								<br>Device: @{{error.device}}
							</td>
						</tr>
					</table>
				</div>
				<div class="panel-footer">
					<button class="btn btn-danger">Delete</button>
				</div>
			</div>
			<div class="text-center">
				<div pagination  class="" ng-cloak
					total-items="errorsCount"
					max-size="10"
					ng-model="params.page"
					previous-text="Prev"
					boundary-links="true"
				></div>
			</div>
		</div>
	</div>
@stop