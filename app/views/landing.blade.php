<html>
	<head>
		<title>Angulytics - Error reporting for Angular apps</title>
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
	            	Angulytics
	            </a>
	          </div>
	          <div id="navbar-collapse-1" class="collapse navbar-collapse">
	            <ul class="nav navbar-nav navbar-right">
	              <li><a href="#home">Home</a></li>
	              <li><a href="#banner">Download</a></li>
	              <li><a href="#features">Features</a></li>
	              <li><a href="#details">Details</a></li>
	              <li><a href="#testimonials">Testimonials</a></li>
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
		                	<p>Add the module and set your endpoint. That's it.</p>
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
		            <div class="feature-item col-md-4 text-center"><i class="fa fa-refresh"></i>
		            	<h3>HTTP Error Monitoring</h3>
	            		<div class="desc">
	                		<p>Your app needs ajax to work. We watch your http requests to make sure it does.</p>
	              		</div>
	            	</div>
	        	</div>
	      </div>
	      <div id="details" class="details">
	        <ul class="list-details list-unstyled">
	          <li>
	            <div class="container">
	              <div class="detail-item clearfix">
	                <div class="detail-desc">
	                  <h2>Responsive Design</h2>
	                  <p>Auto adjust layout according to screen size. <br>Looks good on desktop, tablet and phone.</p>
	                </div>
	                <div class="detail-img"><img src="assets/preview-responsive.png" alt="img"></div>
	              </div>
	            </div>
	          		</li>
		          	<li class="alt">
		            	<div class="container">
		              	<div class="detail-item clearfix">
		                	<div class="detail-desc">
		                  	<h2>Multiple Layouts</h2>
		                  		<p>Wide and boxed layout.<br>vertical and horizaon navigation，<br>toggle for fixed top header and sidebar menu.</p>
		                	</div>
		                	<div class="detail-img"><img src="assets/preview-layouts.png" alt="img"></div>
		              		</div>
		            	</div>
	          		</li>
	          		<li>
	        			<div class="container">
		              		<div class="detail-item clearfix">
				                <div class="detail-desc">
				                  	<h2>Frequently Asked Questions</h2>
				                  	<p>Single Page Application Built with AngularJS. <br>Fully AJAX powered, never refresh the entire page again.<br>Faster, smoother.</p>
				                </div>
                				<div class="detail-img"><img src="assets/preview-app.png" alt="img"></div>
	              			</div>
	            		</div>
	          		</li>
	        	</ul>
	      	</div>
	      	<div id="testimonials" class="testimonials">
	        	<div id="testimonials-carousel" data-ride="carousel" class="carousel slide">
		          	<ol class="carousel-indicators">
		            	<li data-target="#testimonials-carousel" data-slide-to="0" class="active"></li>
		            	<li data-target="#testimonials-carousel" data-slide-to="1"></li>
		            	<li data-target="#testimonials-carousel" data-slide-to="2"></li>
		            	<li data-target="#testimonials-carousel" data-slide-to="3"></li>
		            	<li data-target="#testimonials-carousel" data-slide-to="4"></li>
		          	</ol>
	          		<div class="carousel-inner text-center">
	            	<div class="item active">
	              		<p>Great job! It's simply wonderful!</p><small>- Comments from buyer</small>
	            	</div>
	            	<div class="item">
	              		<p>Very impresive for being the first project here at wrapBootstrap and the very first angularjs theme amazing! Lot of plugins that are useful and not just inflate the package.</p><small>- Comments from buyer</small>
	            	</div>
	            	<div class="item">
	              		<p>Great Job! First Angular JS Template on wrapBootstrap!</p><small>- Comments from buyer</small>
	            	</div>
	            		<div class="item">
	              			<p>Really awesome that you're the first one who creates an AngularJS for wrapBootstrap. I've been looking for one for a long time, and it's really annoying to convert a non AngularJS theme to an AngularJS theme, so thanks for making my life easier.</p><small>- Comments from buyer</small>
	            		</div>
	            		<div class="item">
	              			<p>Amazing! Exact what I looking for.</p><small>- Comments from buyer</small>
	            		</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>
	</body>
</html>