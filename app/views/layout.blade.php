<html route="{{Route::getCurrentRoute()->getPath()}}">
<head>
	<title>Consulting</title>
	{{HTML::style('/components/bootstrap/dist/css/bootstrap.min.css')}}
	{{HTML::style('/css/slim.css')}}
	{{HTML::style('/fonts/themify-icons/themify-icons.min.css')}}
	{{HTML::style('/css/style.css')}}
</head>
<body ng-app="app">
	<nav class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" ng-click="isNavOpen = !isNavOpen">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Consulting
				</a>
			</div>
			<div collapse="!isNavOpen" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="/profile">Profile</a></li>
					<li><a href="/messages">Messages</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="/login">Login</a></li>
						<li><a href="/join">Register</a></li>
					@else
						<li class="dropdown" dropdown>
							<a href="#" class="dropdown-toggle" dropdown-toggle>
								{{ Auth::user()->name ? Auth::user()->name : 'Me' }}
								&nbsp;<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/settings">Settings</a></li>
								<li><a href="/auth/logout">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	<textarea frontload="csrfToken">{{csrf_token()}}</textarea>
	<textarea frontload="inputs" frontload-type="json">{{json_encode(Input::all())}}</textarea>
	<textarea frontload="constants" frontload-type="json">{{json_encode(Config::get('constants'));}}</textarea>
	@if(Auth::user())
		<textarea frontload="me" frontload-type="json">{{Auth::user()}}</textarea>
	@endif
	@if(Session::get('growlMessages'))
		<textarea frontload="growlMessages" frontload-type="json">{{json_encode(Session::get('growlMessages'))}}</textarea>
	@endif
	@yield('content')
	<script src="https://checkout.stripe.com/checkout.js"></script>
	{{HTML::script('/components/underscore/underscore-min.js')}}
	{{HTML::script('/components/angular/angular.js')}}
	{{HTML::script('/components/angular-bootstrap/ui-bootstrap-tpls.min.js')}}
	{{HTML::script('/components/angular-httpi/lib/httpi.js')}}
	{{HTML::script('/components/angular-timeago/src/timeAgo.js')}}
	{{HTML::script('/components/angular-simple-storage/dist/angular-simpleStorage.js')}}
	{{HTML::script('/components/angular-underscore/angular-underscore.min.js')}}
	{{HTML::script('/components/angular-growl/build/angular-growl.min.js')}}
	{{HTML::script('/components/angular-watchdog/dist/angular-watchdog.min.js')}}
	{{HTML::script('/js/frontloader.js')}}
	{{HTML::script('/js/isoform.js')}}
	{{HTML::script('/js/app.js')}}
	<div growl></div>
</body>
</html>