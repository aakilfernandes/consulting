@extends('layout')

@section('content')
<div class="container">
	<h1>Account</h1>		
	You are currently on the {{Auth::user()->plan['name']}} plan.
</div>
@stop