@extends('layout')

@section('content')
	<textarea frontload="bucket_id" frontload-type="integer">{{$bucket->id}}</textarea>
	<div class="container">
		<h1>{{$bucket->name}} Errors</h1>
		<div ng-controller="ErrorsController">
			<div class="panel panel-default" ng-repeat="error in errors" ng-cloak>
				<div class="panel-body">
					<button show-stack="error.stack">Show Stack</button>
				</div>
			</div>
		</div>
	</div>
@stop