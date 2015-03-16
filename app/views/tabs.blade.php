<div class="container">
	<ul class="nav nav-tabs">
		@foreach(Config::get('constants.tabs') as $key=>$tabName)
			@if($key==$tabId)
				<li class="active">
			@else
				<li>
			@endif
			<a href="/{{$key}}">{{$tabName}}</a></li>
		@endforeach
	</ul>
</div>