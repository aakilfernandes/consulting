<html>
<head>
	{{HTML::style('/components/bootstrap/dist/css/bootstrap.min.css')}}
	{{HTML::style('/css/style.css')}}
</head>
<body ng-app="app">
	@yield('content')
	{{HTML::script('/components/angular/angular.min.js')}}
	{{HTML::script('/components/angular-bootstrap/ui-bootstrap-tpls.min.js')}}
	{{HTML::script('/js/app.js')}}
</body>
</html>