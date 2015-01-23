@extends('layout')

@section('content')
<textarea frontload="buckets" frontload-type="json">
	{{Auth::user()->buckets}}
</textarea>
<div class="container" ng-controller="BucketsController">
	<h1>Buckets <button class="btn btn-primary" ng-click="new()">New</button></h1>
	<div class="panel panel-default" ng-repeat="bucket in buckets | reverse" ng-cloak>
		<div class="panel-heading">
			<h4 class="panel-title">
				<a ng-href="/buckets/@{{bucket.id}}/profiles">@{{bucket.name}}</a>
				<small class="glyphicon glyphicon-edit text-muted" ng-click="editName(bucket)" style="top:-2px"></small>
			</h4>
		</div>
		<div class="panel-body">
			<p>
				<b>Config Key:</b> @{{bucket.key}}
			</p>
			<textarea class="form-control" ng-model="bucket.script">
			</textarea>
		</div>
	</div>
</div>
@stop