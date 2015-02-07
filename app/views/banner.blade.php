@if(!Auth::user()->hasEverSubscribed)
	@if(!Auth::user()->trialBeganAt)
		<div class="alert alert-info">
			You haven't installed Angulytics on any of your sites. Once you do, you'll have {{Config::get('constants.trialDays')}} days to <a class="btn btn-danger btn-xs" checkout="/api/user/upgrade" growl-start="Upgrading" growl-success="Upgrade complete">upgrade your account</a>
		</div>
	@else
		<div class="alert alert-danger">
			@if(Auth::user()->trialDaysLeft<2)
				You have less than 48 hours left in your trial. Upgrade soon!
			@else
				You have {{Auth::user()->trialDaysLeft}} left in your trial.
			@endif
		</div>
	@endif
@elseif(Auth::user()->isOnGracePeriod)
	@if(Auth::user()->subscription_ends_at->diffInDays()>=2)
		<div class="alert alert-danger">
			Your subscription will end in {{Auth::user()->subscription_ends_at->diffInDays()}} days.
			<a class="btn btn-danger btn-xs" checkout="/api/user/resume">Resume Subscription</a>
		</div>
	@else
		<div class="alert alert-danger">
			Your subscription will end in {{Auth::user()->subscription_ends_at->diffInHours()}} hours.
			<a class="btn btn-danger btn-xs" checkout="/api/user/resume">Resume Subscription</a>
		</div>
	@endif
@endif