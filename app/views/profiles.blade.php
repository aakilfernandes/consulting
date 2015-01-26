@extends('layout')

@section('content')
	<textarea frontload="bucket" frontload-type="json">{{$bucket}}</textarea>

	<div class="container">
		<ol class="breadcrumb">
		  	<li><a href="/">Home</a></li>
		  	<li><a href="/buckets">Buckets</a></li>
		  	<li>{{$bucket->name}}</li>
		  	<li>Profiles</li>
		</ol>
		<h1>{{$bucket->name}} Error Profiles</h1>
		<div ng-controller="ProfilesController">
			<div class="row">
				<div class="col-sm-3">
					<div class="row">
						<div class="col-xs-6 col-sm-12">
							<select class="form-control"
								ng-model="params.filters.status_id"
								ng-options="statusFilter.id as statusFilter.label for statusFilter in statusFilters"
							></select>
						</div>
						<div class="col-xs-6 col-sm-12">
							<hr class="hidden-xs">
							<select class="form-control"
								ng-model="params.sort"
								ng-options="sort.id as sort.label for sort in sorts"
							></select>
						</div>
					</div>	
				</div>
				<div class="col-sm-9">
					<div class="panel" ng-show="response.total>params.pageSize" ng-cloak>
						<div class="panel-heading text-center">
							<div pagination  class="" ng-cloak
								total-items="response.total"
								max-size="7"
								ng-model="params.page"
								previous-text="Prev"
								boundary-links="true"
								items-per-page="params.pageSize"
							></div>
						</div>
					</div>
					<div class="alert alert-info text-center" show-debounced="isLoading">Loading Profiles</div>
					<div class="alert alert-warning text-center" ng-cloak ng-show="!isLoading && response.total===0">
						No Profiles
					</div>
					<div class="panel panel-default" ng-repeat="profile in profiles" ng-cloak profile="profile" ng-hide="params.filters.status_id && profile.status_id != params.filters.status_id">
						<div class="panel-body">
							<span class="label label-warning label-xs notification" alt="Errors Count" tooltip="errors count" >@{{profile.errorsCount}}</span>
							<div class="row">
								<div class="col-sm-8">
									<b>
										@{{profile.alias}}
									</b>
									<div class="text-muted">
										@{{profile.lastError.stack[0].url | withoutFileName}}/<b>@{{profile.lastError.stack[0].url | fileName}}</b>
										line <b>@{{profile.lastError.stack[0].line}}</b>
										column <b>@{{profile.lastError.stack[0].column}}</b>
										<a alt="View javascript file" ng-href="@{{profile.lastError.stack[0].url}}" class="ti-link" target="_blank"></a>
										<br>
											Last seen <b>@{{profile.lastError.created_at+' +00' | timeAgo}}</b>,
											First seen <b>@{{profile.created_at+' +00' | timeAgo}}</b>
									</div>
								</div>
								<hr class="hidden-sm hidden-md hidden-lg hidden-xl">
								<div class="col-sm-4 text-right">
									<a ng-show="profile.documentationLink" alt="View Angular Documentation" ng-href="@{{profile.documentationLink}}" target='_blank' class="btn btn-xs btn-info">Docs</a>
									<a show-stack="profile.lastError.stack" class="btn btn-xs btn-info">Stack</a>
									<a class="btn btn-xs btn-primary"
										href="/buckets/@{{frontloaded.bucket.id}}/profiles/@{{profile.id}}"
										>Explore</a>
									<a class="btn btn-xs btn-danger"
										href="/buckets/@{{frontloaded.bucket.id}}/profiles/@{{profile.id}}"
										>Delete</a>
									<div style="height:5px"></div>
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
		</div>
	</div>
@stop