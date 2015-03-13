var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'angular-growl'
]).run(function($rootScope,frontloaded){
	$rootScope.frontloaded = frontloaded
})

app.config(function($provide,$compileProvider,$httpProvider,growlProvider,IsoformProvider){
	$provide.decorator('$http',function($delegate,frontloaded){
		$delegate.defaults.transformRequest.push(function(dataJson){
			var data = dataJson ? angular.fromJson(dataJson) : {}
			data._token = frontloaded.csrfToken
			return angular.toJson(data)
		})

		return $delegate
	})

	growlProvider.globalTimeToLive(2000);
	growlProvider.onlyUniqueMessages(false);

})

app.run(function($rootScope,$http,frontloaded,Isoform,growl) {

	$rootScope.frontloaded = frontloaded
	$http.defaults.headers.delete = { 'Content-Type' : 'application/json' };

	growl.add = function(type,message){
		type = type.charAt(0).toUpperCase() + type.slice(1)

		growl['add'+type+'Message'](message)
	}

	$rootScope.$watch('frontloaded.growlMessages',function(value,oldValue){
		if(!value) return
		value.forEach(function(args){
			growl.add.apply(growl,args)
		})
	})

	Isoform.prototype.doBeforeAjaxValidation = function(){
		if(this.isGrowlSupressed)
			return

		growl.add('info','Validating inputs')
	}

	Isoform.prototype.doAfterAjaxValidation = function(response){

		if(this.isGrowlSupressed)
			return

		if(Object.keys(response.data).length==0)
			growl.add('success','Looks good so far')

		Object.keys(response.data).forEach(function(field){
			if(response.data[field].length>0)
				response.data[field].forEach(function(message){
					growl.add('error',message)
				})
		})
	}
});

app.controller('AccountController',function($scope,httpi,language){
	$scope.cancel = function(){

		if(!confirm(language.accountCancel)) return

		httpi({
			method:'post'
			,url:'/api/user/cancel'
		}).success(function(){
			window.location.reload()
		})
	}
})

app.controller('JoinController',function($scope,$timeout){
	$timeout(function(){
		$scope.isoform.isGrowlSupressed = true
	})
	$scope.country = 'US'
	$scope.isAvailable = true
	$scope.isNotifiedOfRequests = true
})


app.controller('UserController',function($scope,frontloaded){
	$scope.user = frontloaded.user
})

app.controller('PasswordController',function($scope,frontloaded){
	$scope.user = frontloaded.user
})

app.controller('BucketsController',function($scope,httpi,language,frontloaded,growl){
	$scope.buckets = frontloaded.buckets

	$scope.new = function(){
		var name = window.prompt(language.bucketName);
		if(name===null) return

		growl.add('info','Creating new bucket')

		httpi({
			method:'POST'
			,url:'/api/buckets'
			,data:{name:name}
		}).success(function(bucket){
			growl.add('success','Bucket created')
			$scope.buckets.unshift(bucket)
		})
	}

	$scope.editName = function(bucket,index){
		var name = window.prompt(language.bucketName)
		if(name===null) return

		growl.add('info','Editing bucket name')

		bucket.name = name

		httpi({
			method:'PUT'
			,url:'/api/buckets/:id'
			,data:bucket
		}).success(function(){
			growl.add('success','Bucket name edited')
		})
	}

	$scope.delete = function(bucket,index){
		if(!confirm(language.bucketDelete)) return

		growl.add('info','Deleting bucket')

		httpi({
			method:'DELETE'
			,url:'/api/buckets/:id'
			,data:bucket
		}).success(function(){
			growl.add('success','Bucket deleted')
		})
		$scope.buckets.splice(index,1)
	}

	$scope.verifyInstallation = function(bucket,$event){
		if(bucket.isInstalled) return

		if(!confirm(language.bucketNotInstalled))
			$event.preventDefault()
	}

	$scope.editSubscription = function(subscription){
		growl.add('info','Updating subscription')
		httpi({
			method:'PUT'
			,url:'/api/subscriptions/:id'
			,data:subscription
		}).success(function(){
			growl.add('success','Subscription updated')
		})
	}

})

app.controller('ProfilesController',function($scope,httpi,$local,$filter,growl){

	$scope.profiles = []
		
	$scope.statusFilters = Object.keys($scope.frontloaded.constants.statuses).map(function(key){
		return {id:key,label:$scope.frontloaded.constants.statuses[key]}
	})
	$scope.statusFilters.unshift({id:undefined,label:'Any Status'})

	$scope.sorts = [
		{id:'oldest',label:'Oldest'}
		,{id:'recentlySeen',label:'Recently Seen'}
		,{id:'recentlyCreated',label:'Recently Created'}
		,{id:'mostErrors',label:'Highest Errors Count'}
	]

	var filters = $local.get('profilesFilters') ? $local.get('profilesFilters') : {}

	$scope.params = {
		bucket_id: $scope.frontloaded.bucket.id
		,filters: {
			status: 'open'
		}
		,sort: 'recentlyCreated'
		,pageSize:5
	}

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		$local.set('profilesStatusFilter',$scope.params.filters.status)
		$local.set('profilesSort',$scope.params.sort)
		loadProfiles()
	},true)

	$scope.$watch('profiles',function(profiles){
		$scope.profilesFiltered = $filter('filterIf')(profiles,{status:$scope.params.filters.status});
	},true)


	function loadProfiles(){
		growl.add('info','Loading error profiles')
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles'
			,params:angular.copy($scope.params)
		}).success(function(response){
			$scope.response = response
			$scope.profiles = response.data
			growl.add('success','Error profiles loaded')
		})
	}

	$scope.updateProfile = function(profile){
		httpi({
			method:'PUT'
			,url:'/api/buckets/:bucket_id/profiles/:id'
			,data:angular.copy(profile)
		})
	}

	loadProfiles()

})

app.directive('showStack',function($modal,growl){
	return {
		scope:{
			stack:'=showStack'
		},
		link: function(scope, element, attributes, ngModel) {

			element.on('click',function () {

				growl.add('info','Loading stack trace')

			    var modalInstance = $modal.open({
			    	templateUrl: '/templates/stackModal.html',
			    	controller: 'StackModalController',
			    	resolve: {
			    		stack: function () { return scope.stack }
			    	}
			    });
			});
	    }
	}
})

app.controller('StackModalController', function($scope,$modalInstance,stack){
	$scope.stack = stack
	$scope.ok = function () {
    	$modalInstance.dismiss('cancel');
  	};
})

app.controller('ErrorsController',function($scope,httpi,$local,growl){

	$scope.errors = []

	$scope.params={
		page:0
		,pageSize:5
		,bucket_id:$scope.frontloaded.bucket.id
		,profile_id:$scope.frontloaded.profile.id
		,filters:{}
	}
	
	loadErrors()

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		loadErrors()
	},true)

	function loadErrors(){
		growl.add('info','Loading errors')
		$scope.isLoading = true
		$scope.errors = []
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles/:profile_id/errors'
			,params:angular.copy($scope.params)
		}).success(function(response){
			$scope.errors = response.data
			$scope.errorsCount = response.total
			$scope.isLoading = false
			growl.add('success','Errors loaded')
		})
	}

	$scope.filterByClient = function (client){
		$scope.params.filters = {
			browser:client.browser
			,os:client.os
			,device:client.device
		}
	}

})

app.directive('timestamp',function(){
	return {
		scope:{timestamp:'=timestamp'}
		,templateUrl:'/templates/timestamp.html'
	}
})

app.factory('hapi',function($rootScope,httpi){
	return function(method,url,params,error){
		var cleanParams = {};
		if(params)
			Object.keys(params).forEach(function(param){
				if(typeof params[param] == 'function')
					return true

				if(param[0]=='$')
					return true

				cleanParams[param]=params[param]
			})

		var promise = 
			httpi({
			    method: method,
			    url: url,
			    params: cleanParams
			})
			.error(function(response){
				if(response.error.message)
					alert('Error: '+response.error.message)
				else
					alert('Something went wrong')

				if(error) error()
			})

		promise.withBlocker = function(){
			$rootScope.isBlocker = true
			promise.finally(function(){
				$rootScope.isBlocker = false
			})
			return promise
		}

		return promise
	}
})

app.factory('language', function() {
  return {
  	bucketName:'What should we name your bucket?'
  	,bucketDelete:'Are you sure? Delting this bucket will also delete the data associated with it'
  	,bucketNotInstalled:"You haven't installed this bucket yet, so there won't be anything to explore. Continue anyways?"
  	,accountCancel:"Are you sure?"
  }
});

app.factory('serialize',function(){
	return function(obj, prefix) {
	  	var str = [];
	  	for(var p in obj) {
	    	if (obj.hasOwnProperty(p)) {
	      		var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
	      		str.push(typeof v == "object" ?
	        		serialize(v, k) :
	        		encodeURIComponent(k) + "=" + encodeURIComponent(v));
	    	}
	  	}
	  	return str.join("&");
	}
})

app.factory('urlJson',function(){
	return function(obj) {
	  	return encodeURIComponent(angular.toJson(obj))
	}
})

app.factory('httpRequests',function(){
	return {}
})

app.filter('localTime', function($filter) {
  return function(timestamp) {
  	var date = new Date(timestamp+' +00')
  	return $filter('date')(date, 'MMM d, y')+' at '+$filter('date')(date, 'h:mm:ss a')
  };
});

app.filter('reverse', function() {
  return function(items) {
  	if(!items) return
    return items.slice().reverse();
  };
});

app.filter('whereIn', function() {
  return function(items,property,values) {
  	if(!values) return items
  	return items.filter(function(item){
  		return values.indexOf(item[property]) !== -1
  	})
  };
});

app.filter('fileName', function() {
  return function(url) {
  	if(!url) return
    var urlParts = url.split('/')
    return urlParts[urlParts.length-1]
  };
});

app.filter('filterIf', function() {
  return function(values,params) {

  	Object.keys(params).forEach(function(key){
  		if(!params[key]) delete params[key]
  	})

  	return _.where(values,params)

  };
});


app.filter('withoutFileName', function() {
  return function(url) {
  	if(!url) return
    var urlParts = url.split('/')
    urlParts.splice(urlParts.length-1,1)
  	return urlParts.join('/')
  };
});

app.directive('showDebounced',function($timeout){
	return {
		scope:{
			isShowing:'=showDebounced'
		},link:function(scope,element){
			element.css('display',scope.isShowing?'':'none')
			var timeout
			scope.$watch('isShowing',function(value,oldValue){
				if(angular.equals(value,oldValue)) return

				if(!value){
					element.css('display','')
					return
				}

				if(timeout)
					$timeout.cancel(timeout)
				

				timeout = $timeout(function(){
					element.css('display','none')
				},1000)
			})
		}
	}
})

app.directive('checkout',function(frontloaded,httpi,growl){
	return {
		scope:{
			url:'@checkout'
			,growlStart:'@?growlStart'
			,growlSuccess:'@?growlSuccess'
		}
		,link:function(scope,element){
			var handler = StripeCheckout.configure({
			    	key: frontloaded.constants.stripeKey
			    	,token: function(token) {
			    		if(scope.growlStart)
			    			growl.add('info',scope.growlStart)

				    	httpi({
				    		method:'post'
				    		,url:scope.url
				    		,data:token
				    	}).success(function(){
				    		if(scope.growlSuccess)
				    			growl.add('success',scope.growlSuccess)

				    		window.location.reload()
				    	})
				    }
			});


			element.bind('click',function(e){
				handler.open({
			    	name: 'Angulytics'
			    	,email: frontloaded.user.email
			    	,amount: frontloaded.constants.plans['hacker'].amount
			    	,panelLabel: '{{amount}}/Month'
			    	,allowRememberMe:false
			    });
			    e.preventDefault();
			})
		}
	}
})