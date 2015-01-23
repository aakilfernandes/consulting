@extends('layout')

@section('content')
	<textarea frontload="bucket_id" frontload-type="integer">{{$bucket->id}}</textarea>
	<div class="container">
		<h1>{{$bucket->name}} Error Profiles</h1>
		<div ng-controller="ProfilesController">
			<div class="panel panel-default" ng-repeat="profile in profiles" ng-cloak>
				<div class="panel-heading">
					<h3 class="panel-title">
						<a ng-show="profile.documentationLink" class="glyphicon glyphicon-book" alt="View Angular Documentation" ng-href="@{{profile.documentationLink}}" target='_blank'></a>
						@{{profile.alias}}
					</h3>
					<span class="text-muted">
						@{{profile.lastError.stack[0].url | withoutFileName}}/<b>@{{profile.lastError.stack[0].url | fileName}}</b>
						line <b>@{{profile.lastError.stack[0].line}}</b>
						column	<b>@{{profile.lastError.stack[0].column}}</b>
						<a href="glyphicon glyphicon-link" alt="View javascript file" ng-href="@{{profile.lastError.stack[0].url}}" class="glyphicon glyphicon-link" target="_blank"></a>
					</span>
				</div>
				<div class="panel-body">
					<table class="table">
						<tr>
							<td>Total Hits</td>
							<td>@{{profile.errorsCount}}</td>
						</tr>
						<tr>
							<td>Last Seen</td>
							<td>@{{profile.lastError.created_at}}</td>
						</tr>
						<tr>
							<td>Effected Clients</td>
							<td>
								<table class="table">
									<tr>
										<th>Browser</th>
										<th>Os</th>
										<th>Device</th>
									</tr>
									<tr ng-repeat="client in profile.clients">
										<td>@{{client.browser}}</td>
										<td>@{{client.os}}</td>
										<td>@{{client.device}}</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop