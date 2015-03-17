<html>
<head>
	<title>Consulting</title>
	{{HTML::style('/components/bootstrap/dist/css/bootstrap.min.css')}}
	{{HTML::style('/css/slim.css')}}
	{{HTML::style('/fonts/themify-icons/themify-icons.min.css')}}
	{{HTML::style('/css/style.css')}}
</head>
<body ng-app="app">
	@include('nav')
	<textarea frontload="csrfToken">{{csrf_token()}}</textarea>
	<textarea frontload="inputs" frontload-type="json">{{json_encode(Input::all())}}</textarea>
	<textarea frontload="constants" frontload-type="json">{{json_encode(Config::get('constants'));}}</textarea>
	@if(Auth::user())
		<textarea frontload="me" frontload-type="json">{{Auth::user()->withHidden()}}</textarea>
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