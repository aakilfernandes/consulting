@extends('layout')

@section('content')
	<textarea frontload="bucket" frontload-type="json">{{$bucket}}</textarea>
	<textarea frontload="profile" frontload-type="json">{{$profile}}</textarea>
	<div class="container">
		<ol class="breadcrumb">
		  	<li><a href="/">Home</a></li>
		  	<li><a href="/buckets">Buckets</a></li>
		  	<li>{{$bucket->name}}</li>
		  	<li><a href="/buckets/{{$bucket->id}}/profiles">Profiles</a></li>
		  	<li class="active">{{$profile->alias}}</li>
		</ol>
		<h2>{{$profile->alias}}</h2>
		<div class="row" ng-controller="ErrorsController">
			<div class="col-sm-5">
				<table class="table clients">
					<tr>
						<th>Browser</th>
						<th>OS</th>
						<th>Device</th>
						<th></th>
						<th><button class="btn btn-xs btn-primary"
							ng-show="frontloaded.profile.clients.length>1 && (params.filters.browser || params.filters.os || params.filters.device)"
							ng-click="filterByClient({})"
						>View All</button></th>
					</tr>
					<tr class="client" ng-repeat="client in frontloaded.profile.clients" ng-cloak>
						<td>@{{client.browser}}</td>
						<td>@{{client.os}}</td>
						<td>@{{client.device}}</td>
						<td>@{{client.errorsCount}}</td>
						<td><button class="btn btn-xs btn-primary"
							ng-show="frontloaded.profile.clients.length>1 && (
								params.filters.browser != client.browser
								|| params.filters.os != client.os
								|| params.filters.device != client.device
							)"
							ng-click="filterByClient(client)"
						>Filter</button></td>
					</tr>
				</table>
			</div>
			<div class="col-sm-7">
				<div class="panel" ng-show="errorsCount>params.pageSize" ng-cloak>
					<div class="panel-heading text-center">
						<div pagination  class="" ng-cloak
							total-items="errorsCount"
							max-size="7"
							ng-model="params.page"
							previous-text="Prev"
							boundary-links="true"
							items-per-page="params.pageSize"
						></div>
					</div>
				</div>
				<div class="alert alert-info text-center" show-debounced="isLoading">Loading Errors</div>
				<div class="panel panel-default" ng-repeat="error in errors" ng-cloak>
					<div class="panel-body">
						<div class="row">
							<div class="col col-xs-4" timestamp="error.created_at"></div>
							<div class="col col-xs-5">
								<b>@{{error.browser}}</b>, <b>@{{error.os}}</b>, <b>@{{error.device}}</b>							
							</div>
							<div class="col col-xs-3 text-right">
								<button class="btn btn-primary btn-xs" show-stack="error.stack">Show Stack</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop