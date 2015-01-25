@extends('layout')

@section('content')
	<textarea frontload="bucket" frontload-type="json">{{$bucket}}</textarea>

	<div class="container">
		<ol class="breadcrumb">
		  	<li><a href="/">Home</a></li>
		  	<li><a href="/buckets">Buckets</a></li>
		  	<li><a href="/buckets/{{$bucket->id}}">{{$bucket->name}}</a></li>
		  	<li class="active">Profiles</li>
		</ol>
		<h1>{{$bucket->name}} Error Profiles</h1>
		<div ng-controller="ProfilesController">
			<div class="row">
				<div class="col-sm-4">
					<select class="form-control"
						ng-model="params.filters.status_id"
						ng-options="statusFilter.status_ids as statusFilter.label for statusFilter in statusFilters"
					></select>
				</div>
				<div class="col-sm-4 col-xs-6">
					<select class="form-control"
						ng-model="params.sort"
						ng-options="sort.id as sort.label for sort in sorts"
					></select>
				</div>
			</div>
			<hr>
			<div class="panel panel-default" ng-repeat="profile in profiles | whereIn:'status_id':params.filters.status_id" ng-cloak profile="profile">
				<div class="panel-heading">
					<div class="row">
						<div class="col-sm-10">
							<h3 class="panel-title">
								<a ng-show="profile.documentationLink" class="glyphicon glyphicon-book" alt="View Angular Documentation" ng-href="@{{profile.documentationLink}}" target='_blank'></a>
								<a ng-href="/buckets/@{{frontloaded.buckets.id}}/profiles/@{{profile.id}}">@{{profile.alias}}<a>
							</h3>
							<div class="text-muted">
								@{{profile.lastError.stack[0].url | withoutFileName}}/<b>@{{profile.lastError.stack[0].url | fileName}}</b>
								line <b>@{{profile.lastError.stack[0].line}}</b>
								column	<b>@{{profile.lastError.stack[0].column}}</b>
								<a href="glyphicon glyphicon-link" alt="View javascript file" ng-href="@{{profile.lastError.stack[0].url}}" class="glyphicon glyphicon-link" target="_blank"></a>
								<br>
									Last seen <b>@{{profile.lastError.created_at+' +00' | timeAgo}}</b>,
									First seen <b>@{{profile.created_at+' +00' | timeAgo}}</b>
							</div>
						</div>
						<hr class="hidden-sm hidden-md hidden-lg hidden-xl">
						<div class="col-sm-2">
							<span class="label label-danger" alt="Errors Count">@{{profile.errorsCount}}</span>
							<a class="btn btn-xs btn-primary" href="/buckets/@{{frontloaded.buckets.id}}/profiles/@{{profile.id}}">Explore</a>
							<select class="form-control input-sm" ng-model="profile.status_id"
								ng-options="status.id as status.label for status in frontloaded.statuses | orderBy:'order' ">
							</select>
						</div>
					</div>
				</div>
				<div class="panel-body" ng-show="false">
					<table class="table">
						<tr>
							<td>Last Seen</td>
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
								<a ng-click="viewErrors()" class="btn btn-primary" ng-href="/buckets/@{{frontloaded.bucket.id}}/errors">
									View All Errors
								</a>
							</td>
						</tr>
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