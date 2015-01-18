{{HTML::script('/components/angular/angular.min.js')}}
{{HTML::script('/js/app.js')}}

<div ng-app="app">
	<div ng-controller="TestController">
		<form
			{{Isoform::directive('username','email','password')
		}}>
		Username
		<input ng-model="username" isoform-validate="username">
		Email
		<input ng-model="email" isoform-validate="email">
		Password
		<input ng-model="password_confirmation" isoform-validate="password">
		Password (confirm)
		<input ng-model="password" isoform-validate="password_confirmation">
		<br>
		<span>@{{isoform.messages}}</span>
		</form>
	</div>
</div>