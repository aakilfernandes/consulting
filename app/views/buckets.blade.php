@extends('layout')

@section('content')
<textarea frontload="buckets" frontload-type="json">
	{{Auth::user()->buckets}}
</textarea>
<div class="container" ng-controller="BucketsController">
	<h1>Buckets <button class="btn btn-primary" ng-click="new()">New</button></h1>
	<div class="alert alert-info text-center" show-debounced="isLoading">Loading Buckets</div>
	<div class="panel panel-default" ng-repeat="bucket in buckets | reverse" ng-cloak>
		<div class="panel-heading"><div class="row">
			<div class="col-xs-8">
				<h4 class="panel-title">
					<a ng-href="/buckets/@{{bucket.id}}/profiles">@{{bucket.name}}</a>
					<a class="glyphicon glyphicon-edit text-muted" ng-click="editName(bucket)" style="top:-2px"></a>
				</h4>
				12 open error profiles
			</div>
			<div class="col-xs-4 text-right">
				<a ng-href="/buckets/@{{bucket.id}}/profiles" class="btn btn-xs btn-primary">Explore</a>
				<button ng-click="delete(bucket,$index)" class="btn btn-xs btn-danger">Delete</button>
			</div>
		</div></div>
		<div class="panel-body">
			<h4>Installation</h4>
			<table class="table">
				<tr>
					<td>Endpoint</td>
					<td>https://angulytics.com/endpoints/@{{bucket.id}}/@{{bucket.key}}</td>
				</tr>
				<tr>
					<td>Status</td>
					<td>Not installed</td>
				</tr>
			</table>
			<h4>Email Notifications</h4>
			<label><input type="checkbox"> When new error profiles are created</label>
			<br><label><input type="checkbox"> When error profile marked as closed registers a new error</label>
			<br><label><input type="checkbox"> A daily summary</label>
		</div>
	</div>
</div>
@stop