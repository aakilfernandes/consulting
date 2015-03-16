@extends('layout')

@section('content')
<div class="container" ng-controller="MessagesController" ng-cloak>
	<div ng-repeat="message in messages">
		<b>@{{message.name}}</b>
		from <b>@{{message.company}}</b>
		<br>
		<small class="text-muted">
			@{{message.created_at | timeAgo}}
			| @{{message.email}}
		</small>
		<p style="max-width:600px">@{{message.info}}</p>
		<hr>
	</div>
	<button ng-show="!isLoading && response.to<response.total" class="btn btn-primary" ng-click="loadMore()">
		Load More
	</button>
</div>
@stop