<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" ng-click="isNavOpen = !isNavOpen">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">{{Config::get('constants')['brand']}}
			</a>
		</div>
		<div collapse="!isNavOpen" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				@if(Auth::check())
				<li><a href="/profile">Profile</a></li>
				<li><a href="/messages">Messages</a></li>
				@endif
			</ul>
			<ul class="nav navbar-nav navbar-right">
				@if (Auth::guest())
					<li><a href="/login">Login</a></li>
					<li><a href="/join">Join</a></li>
				@else
					<li class="dropdown" dropdown>
						<a href="#" class="dropdown-toggle" dropdown-toggle>
							{{ Auth::user()->name ? Auth::user()->name : 'Me' }}
							&nbsp;<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/settings">Settings</a></li>
							<li><a href="/logout">Logout</a></li>
						</ul>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>