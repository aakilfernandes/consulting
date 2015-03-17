@extends('layout')

@section('content')
<textarea frontload="user" frontload-type="json">{{$user}}</textarea>
<textarea frontload="isEditable" frontload-type="boolean">{{$isEditable}}</textarea>
<div class="container profile" ng-controller="ProfileController">
	@if($isEditable)
		<div class="alert alert-info">
			Want to see how the rest of the world sees this page?
			<a href="{{$user->publicPreviewUrl}}">Click here</a>
		</div>
	@endif
	<div class="row">
		<div class="col-sm-8">
		<table class="table">
				<tr>
					@if($user->usesGravatar)
					<td style="width:120px">
						<img src="{{$user->gravatarUrl}}" class="img-thumbnail">
						<div style="margin-top:5px;text-align:right;" ng-show="frontloaded.isEditable">
							<a class="glyphicon glyphicon-pencil text-muted" href="http://gravatar.com" target="_blank"></a>
							<a class="glyphicon glyphicon-remove-sign text-danger" href="/settings"></a>
						</div>
					</td>
					@endif
					<td>
						<h1>{{$user->name}}</h1>
						<h2>{{$user->tagline}}</h2>
					</td>
				</tr>
			</table>
			<hr>
			<div ng-cloak>
				<h3>Technologies/Skills <button ng-show="frontloaded.isEditable" class="btn btn-primary btn-sm" ng-click="openSkillModal()">New</button></h3>
				<table>
					<tr ng-repeat="skill in user.skills">
						<td><b>@{{skill.name}}</b></td>
						<td>
							<span ng-repeat="level in _.range(frontloaded.constants.levels.max)">
								<span class="glyphicon glyphicon-star @{{level>=skill.pivot.level?'text-muted':''}}"></span>
							</span>
							<span class="icons" ng-show="frontloaded.isEditable">
								<span class="glyphicon glyphicon-pencil text-muted" ng-click="openSkillModal(skill)"></span>
								<span class="text-danger glyphicon glyphicon-remove-sign" ng-click="deleteSkill(skill,$index)"></span>
							</span>
						</td>
					</tr>
				</table>
				<h3>Projects <button ng-show="frontloaded.isEditable" class="btn btn-primary btn-sm" ng-click="openProjectModal()">New</button></h3>
				<div ng-repeat="project in user.projects">
					<span class="icons" ng-show="frontloaded.isEditable">
						<span class="glyphicon glyphicon-pencil text-muted" ng-click="openProjectModal(project)"></span>
						<span class="text-danger glyphicon glyphicon-remove-sign" ng-click="deleteProject(project,$index)"></span>
					</span>
					<a class="project-title" ng-href="@{{project.url}}" target="_blank">@{{project.name}} <span class="glyphicon glyphicon-link"></span></a>
					<p>@{{project.blurb}}</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h6 class="panel-title">{{$user->availabilityString}}</h6>
				</div>
			  <div class="panel-body">
			    <button class="btn btn-danger btn-lg request-button" ng-click="openMessageModal()" ng-disabled="frontloaded.isEditable">Send {{$user->firstName}} a Message</button>
			    @if($user->isEmailPublic)
			    	<center class="text-muted" style="margin-top:10px">{{$user->email}}</center>
			    @endif
			  </div>
			  <div class="panel-footer text-center">no signup requred</div>
			</div>
		</div>
	</div>
</div>
@stop