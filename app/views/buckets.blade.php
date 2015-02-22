@extends('layout')

@section('content')
<textarea frontload="buckets" frontload-type="json">
	{{Auth::user()->buckets}}
</textarea>
<div class="container" ng-controller="BucketsController">
	@include('tabs',['tabId'=>'buckets'])
	<h1>Buckets <button class="btn btn-primary" ng-click="new()">New</button></h1>
	<p>A bucket contains all the data for a single app or website.</p>
	<div class="alert alert-info" ng-cloak ng-show="buckets.length===0">
		You don't have any buckets. You should create one.
	</div>
	<div show-debounced="isLoading" class="alert alert-info" ng-cloak>
		Loading...
	</div>
	<div class="panel panel-default" ng-repeat="bucket in buckets" ng-cloak>
		<div class="panel-heading"><div class="row">
			<div class="col-xs-8">
				<h4 class="panel-title">
					<a ng-href="/buckets/@{{bucket.id}}/profiles">@{{bucket.name}}</a>
					<a class="ti ti-pencil" ng-click="editName(bucket)"
						style="
							margin-top: -2px;
							position: absolute;
							font-size: .7em;
							"
					></a>
				</h4>
				@{{bucket.openProfilesCount}} open error profile@{{bucket.openProfilesCount!=1?'s':''}}
			</div>
			<div class="col-xs-4 text-right">
				<a ng-href="/buckets/@{{bucket.id}}/profiles" class="btn btn-xs btn-primary">Explore</a>
				<button ng-click="delete(bucket,$index)" class="btn btn-xs btn-danger">Delete</button>
			</div>
		</div></div>
		<div class="panel-body">
			<h4>Watchdog Installation</h4>
			<p><code>@{{bucket.watchdogEndpoint}}</code></p>
			<p>Angulytics' official client library is called Angular Watchdog. You can read more about Angular Watchdog and how to install it here.</p>
			<hr>
			<h4>Send me an email notification when: </h4>
			<label>
				<input type="checkbox"
					ng-model="bucket.subscription.profileCreated"
					ng-change="editSubscription(bucket.subscription)">
				New error profiles are created
			</label>
			<br><label>
				<input type="checkbox"
					ng-model="bucket.subscription.profileReopened"
					ng-change="editSubscription(bucket.subscription)">
				Error profile marked as closed registers a new error
			</label>
		</div>
	</div>
</div>
@stop