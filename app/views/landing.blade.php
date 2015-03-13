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
		<header class="header navbar navbar-default navbar-fixed-top">
	      <div class="container">
	        <div class="row">
	          <div class="navbar-header">
	            <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="#" class="navbar-brand">
	            	<img src="/img/logo.png" style="height:1.2em;margin-top:-.2em">
	            	Consulting
	            </a>
	          </div>
	          <div id="navbar-collapse-1" class="collapse navbar-collapse">
	            <ul class="nav navbar-nav navbar-right">
	              <li><a href="#features">Features</a></li>
	              <li><a href="#pricing">Pricing</a></li>
	              <li><a href="/login">Log in</a></li>
	              <li><a href="/join">Join</a></li>
	            </ul>
	          </div>
	        </div>
	      </div>
	    </header>
		<div class="main-content">
	      <div id="banner" class="banner">
	        <div class="container">
	        	<h2>You built an awesome Angular app.<br>We'll tell you if it breaks.</h2>
	        	<img src="assets/preview-simple.png" alt="preview" class="preview-img">
	        </div>
	      </div>
	      <div id="features" class="features">
	        <div class="container">
	          	<div class="row">
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-bolt"></i>
		            	<h3>Quick & Easy Installation</h3>
		            	<div class="desc">
		                	<p>Add the module and set your endpoint. That's it. Check out our <a href="http://angulytics.github.io/integrating-angular-tattler">docs</a>.</p>
		              	</div>
		            </div>
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-warning"></i>
		            	<h3>Instant Notifications</h3>
		            	<div class="desc">
		                	<p>Get an email notification any time we detect a new error profile</p>
		              	</div>
		            </div>
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-cogs"></i>
		              	<h3>Intelligent Error Profiling</h3>
		              	<div class="desc">
		                	<p>We group errors with similar footprints into error profiles so you only get notified of new issues</p>
		              	</div>
		            </div>
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-terminal"></i>
		            	<h3>Stack Traces</h3>
	            		<div class="desc">
	                		<p>You can't open the inspector on your users' browser, but this is the next best thing.</p>
	              		</div>
	            	</div>
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-eye"></i>
		            	<h3>Client Fingerprinting</h3>
		            	<div class="desc">
		                	<p>We'll tell you the browser, device, and operating system where errors occur.</p>
		              	</div>
		            </div>
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-usd"></i>
		            	<h3>Money Back Guarentee</h3>
	            		<div class="desc">
	                		<p>We'll refund your first payment, no questions asked.</p>
	              		</div>
	            	</div>
	        	</div>
	    	</div>
	    	<div id="pricing" class="banner" style="padding-bottom:100px;">
	    		<h2>
	    			Try Angulytics, Risk Free.
	    		</h2>
	    		<p>
	    			On top of a 14 day free trial, we offer a 30 day money back guarentee.
	    		</p>
	    		<div class="row">
	    			<div class="col-sm-2 hidden-xs"></div>
	    			<div class="col-sm-4">
	    				<div class="panel" style="color:#333">
	    					<div class="panel-heading">
	    						<h3>
	    							{{$plan['name']}}:
	    							${{$plan['amount']/100}}/mo
	    						</h3>
	    					</div>
	    					<div class="panel-body" style="text-align:left;min-height:120px;">
	    						<ul>
		    						<li>Unlimited buckets</li>
	    							<li>Store up to {{$plan['savedProfiles']}} error profiles</li>
	    							<li>Store up to {{$plan['savedErrorsPerProfile']}} errors per profile</li>
	    							<li>Receive up to {{$plan['emailsDailyMax']}} emails a day</li>
	    						</ul>
	    					</div>
	    					<div class="panel-footer">
	    						<a class="btn btn-primary" href="/join">
	    							Start {{$constants['trialDays']}} Day Free Trial
	    						</a>
	    					</div>
	    				</div>
	    			</div>
	    			<div class="col-sm-4">
	    				<div class="panel" style="color:#333">
	    					<div class="panel-heading">
	    						<h3>
	    							Custom
	    						</h3>
	    					</div>
	    					<div class="panel-body" style="text-align:left;min-height:120px;">
	    						Need something different? Send us an email and we'll work something out.
	    					</div>
	    					<div class="panel-footer">
	    						<a class="btn btn-primary" href="mailto:aakil@angulytics.com">
	    							Send email
	    						</a>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	    <script>
	    	function scrollToElementWithId(id,offset) {
			    
			    var element = document.getElementById(id)
			    	,offset = offset?offset:0
			    	,elementTop = element.offsetTop + offset
			    	,currentTop = document.body.scrollTop
			    	,step = Math.max(10,Math.abs(elementTop-currentTop)/50)

			    var interval = setInterval(function() {
			    	var margin = elementTop - currentTop

			    	if(margin<step && margin > -step){
			    		window.scrollTo(0,elementTop)
			    		clearInterval(interval)
			    		return
			    	}else if(margin>step)
			    		margin = step
			    	else if(margin<-step)
			    		margin = -step

			    	currentTop += margin
			    	window.scrollTo(0,currentTop)
			    }, 1);
			}

			[].forEach.call(document.querySelectorAll('a[href^="#"]')
				,function(link){
					var targetId = link.getAttribute('href').split('#')[1]

					link.addEventListener('click',function(event){
						event.preventDefault()
						scrollToElementWithId(targetId,-50)
					})
				})
	    </script
	</body>
</html>