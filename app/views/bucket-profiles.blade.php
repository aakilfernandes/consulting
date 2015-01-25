@extends('layout')

@section('content')
	<textarea frontload="bucket_id" frontload-type="integer">{{$bucket->id}}</textarea>
	<div class="container">
		<h1>{{$bucket->name}} Error Profiles</h1>
		<div ng-controller="ProfilesController">
			<div class="row">
				<div class="col-sm-4">
					<select class="form-control"
						ng-model="profilesFilters.status_id"
						ng-options="statusFilter.status_ids as statusFilter.label for statusFilter in statusFilters"
					></select>
				</div>
				<div class="col-sm-4 col-xs-6">
					<select class="form-control"
						ng-model="profilesSort"
						ng-options="profilesSort.id as profilesSort.label for profilesSort in profilesSorts"
					></select>
				</div>
				<div class="col-sm-4 col-xs-6 text-right">
					<button class="btn btn-primary" ng-cloak
						ng-click="setIsCollapsed(true)"
						ng-show="where(profiles,{isCollapsed:true}).length<profiles.length"
					>Collapse all</button>
					<button class="btn btn-primary" ng-cloak
						ng-click="setIsCollapsed(false)"
						ng-show="where(profiles,{isCollapsed:true}).length>0"
					>Expand all</button>
				</div>
			</div>
			<hr>
			<div class="panel panel-default" ng-repeat="profile in profiles" ng-cloak profile="profile">
				<div class="panel-heading">
					<div class="row">
						<div class="col-sm-10">
							<h3 class="panel-title">
								<a ng-show="profile.documentationLink" class="glyphicon glyphicon-book" alt="View Angular Documentation" ng-href="@{{profile.documentationLink}}" target='_blank'></a>
								@{{profile.alias}}
							</h3>
							<div class="text-muted">
								@{{profile.lastError.stack[0].url | withoutFileName}}/<b>@{{profile.lastError.stack[0].url | fileName}}</b>
								line <b>@{{profile.lastError.stack[0].line}}</b>
								column	<b>@{{profile.lastError.stack[0].column}}</b>
								<a href="glyphicon glyphicon-link" alt="View javascript file" ng-href="@{{profile.lastError.stack[0].url}}" class="glyphicon glyphicon-link" target="_blank"></a>
								<br>Last seen <b>@{{profile.lastError.created_at+' +00' | timeAgo}}</b>
							</div>
						</div>
						<hr class="hidden-sm hidden-md hidden-lg hidden-xl">
						<div class="col-sm-2">
							<label>
								<input type="checkbox" ng-model="profile.isCollapsed">
								Collapsed
							</label>
							<br>
							<select class="form-control input-sm" ng-model="profile.status_id"
								ng-options="status.id as status.label for status in statuses | orderBy:'order' ">
							</select>
						</div>
					</div>
				</div>
				<div class="panel-body" ng-show="!profile.isCollapsed">
					<h4>Overview</h4>
					<table class="table">
						<tr>
							<td>Total Hits</td>
							<td>@{{profile.errorsCount}}</td>
						</tr>
						<tr>
							<td>Last Seen</td>
							<td timestamp="profile.lastError.created_at"></td>
						</tr>
						<tr>
							<td>First Seen</td>
							<td timestamp="profile.created_at"></td>
						</tr>
						<tr>
							<td>Explore</td>
							<td>
								<button show-stack="profile.lastError.stack" class="btn btn-primary">
									View Stack
								</button>
								<a ng-click="viewErrors()" class="btn btn-primary" ng-href="/buckets/@{{bucket_id}}/errors">
									View All Errors
								</a>
							</td>
					</table>
					<h4>Effected Clients</h4>
					<table class="table">
						<tr>
							<th>Browser</th>
							<th>Os</th>
							<th>Device</th>
							<th>Count</th>
						</tr>
						<tr ng-repeat="client in profile.clients">
							<td>@{{client.browser}}</td>
							<td>@{{client.os}}</td>
							<td>@{{client.device}}</td>
							<td>@{{client.errorsCount}}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop