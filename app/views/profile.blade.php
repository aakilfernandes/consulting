@extends('layout')

@section('content')
<textarea frontload="user" frontload-type="json">{{$user}}</textarea>
<div class="container" ng-controller="ProfileController">
	<table>
		<tr>
			<td style="width:120px">
				<img src="{{$user->gravatarUrl}}">
			</td>
			<td>
				<h1 ng-bind="user.name">{{$user->name}}</h1>
				<h2 ng-bind="user.title" ng-if="user.title">{{$user->title}}</h2>
			</td>
		</tr>
	</table>
	<hr>
	<div ng-cloak>
		<h3>Technologies/Skills <button class="btn btn-primary btn-sm" ng-click="openSkillModal()">New</button></h3>
		<table>
			<tr ng-repeat="skill in user.skills">
				<td><b>@{{skill.name}}</b></td>
				<td>
					<span ng-repeat="level in _.range(frontloaded.constants.levels.max)">
						<span class="glyphicon glyphicon-star @{{level>=skill.pivot.level?'text-muted':''}}"></span>
					</span>
					<span class="icons">
						<span class="glyphicon glyphicon-pencil text-muted" ng-click="openSkillModal(skill)"></span>
						<span class="text-danger glyphicon glyphicon-remove-sign" ng-click="deleteSkill(skill,$index)"></span>
					</span>
				</td>
			</tr>
		</table>
		<h3>Projects <button class="btn btn-primary btn-sm" ng-click="openProjectModal()">New</button></h3>
		<div ng-repeat="project in user.projects">
			<span class="icons">
				<span class="glyphicon glyphicon-pencil text-muted" ng-click="editProject(project)"></span>
				<span class="text-danger glyphicon glyphicon-remove-sign" ng-click="deleteProject(project,$index)"></span>
			</span>
			<a class="project-title" ng-href="@{{project.url}}" target="_blank">@{{project.name}} <span class="glyphicon glyphicon-link"></span></a>
			<p>@{{project.blurb}}</p>
		</div>
	</div>
</div>
@stop