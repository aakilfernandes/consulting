<?PHP
	$constants = Config::get('constants');
	$plans = $constants['plans'];
	$plan = $plans['hacker'];
?>
<html>
	<head>
		<title>Consulting</title>
		{{HTML::style('/css/landing.css')}}
		{{HTML::style('/components/fontawesome/css/font-awesome.min.css')}}
	</head>
	<body>
		@include('nav')
		<div class="main-content">
      <div id="banner" class="banner">
        <div class="container">
        	<h2>We Help 10x Developers Get 10x Pay*</h2>
        	<span>*$100/hr Minimum</span>
        </div>
      </div>
      <div id="features" class="features">
        <div class="container">
        	<div class="row">
        		<div class="feature-item col-md-4 text-center">
            	<i class="fa fa-user-secret"></i>
            	<h3>Keep Your Rates Private</h3>
            	<div class="desc">
                <p>
                	We don't require you to publish your rates, allowing you to increase your rates when you're busy and decrease them when you're looking for work.
                </p>
              </div>
            </div>
            <div class="feature-item col-md-4 text-center">
            	<i class="fa fa-clock-o"></i>
            	<h3>Save Time with Rate Matching</h3>
            	<div class="desc">
                <p>
                	We require recruiters to include a maximum hourly rate. Although we don't share that with you, we use it to filter offers below your minimum rate.
                </p>
              </div>
            </div>
            <div class="feature-item col-md-4 text-center"><i class="fa fa-unlock-alt"></i>
            	<h3>No Paywalls or Lock-Ins</h3>
            	<div class="desc">
                <p>
                	We're not interested in building another LinkedIn. Prospective clients can message you without signing up for {{Config::get('constants')['brand']}} or paying us a fee. Your email stays private (unless you ask us to share it).
                </p>
              </div>
            </div>
          </div>
      	</div>
    	</div>
  	</div>
	</body>
</html>