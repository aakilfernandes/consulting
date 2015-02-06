@extends('layout')

@section('content')
<div class="container">
	<h1>Account</h1>		
	@if(!Auth::user()->everSubscribed())
		<button id="upgrade" class="btn btn-primary">Upgrade Your Account Now</button>
	@endif
</div>
@stop