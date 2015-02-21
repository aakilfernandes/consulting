<?PHP

return [
	'statuses' =>[
			'open'=>'Open'
			,'closed'=>'Closed'
			,'ignored'=>'Ignored'
		]
	,'subscriptions'=>['errorProfilesCreated','errorProfilesOpened','dailySummary']
	,'plans'=>[
		'hacker'=>[
			'amount'=>1200
			,'name'=>'Hacker'
			,'savedProfiles'=>100
			,'savedErrorsPerProfile'=>100
			,'emailsDailyMax'=>10
		]
	],'defaultPlanId'=>'hacker'
	,'trialDays'=>14
	,'stripeKey'=>'pk_test_XrJT5C9ipwgxQpcoERCVF0xY'
	,'tabs'=>[
		'buckets'=>'Buckets'
		,'account'=>'Account'
	]
];