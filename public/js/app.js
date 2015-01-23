var app = angular.module('app',['isoform','frontloader','httpi','angulytics','ui.bootstrap'])

app.config(function(angulyticsProvider){
	angulyticsProvider.$get = function(){
		this.endpoint = 'http://localhost:8000/endpoints/1'
		this.key = '8f33fdc9de2e520c42f6cc5b'
		console.log('angulytics configured',this)
		return this
	}
})

app.controller('BucketsController',function($scope,hapi,frontloaded,language){
	$scope.buckets = frontloaded.buckets

	$scope.new = function(){
		var name = window.prompt(language.bucketName);
		if(name===null) return

		hapi('POST','/api/buckets',{name:name})
			.success(function(bucket){
				$scope.buckets.push(bucket)
			})
			.withBlocker()
	}

	$scope.editName = function(bucket,index){
		var name = window.prompt(language.bucketName)
		if(name===null) return

		bucket.name = name

		hapi('POST','/api/buckets/:id',bucket).success(function(bucket){
			$scope.buckets[index] = bucket
			console.log($scope.buckets)
		})
	}

})

app.controller('ProfilesController',function($scope,httpi,frontloaded,language){
	httpi({
		method:'get'
		,url:'/api/buckets/:bucket_id/profiles'
		,params:{
			bucket_id:frontloaded.bucket_id
		}
	}).success(function(profiles){
		$scope.profiles = profiles
	})
})


app.directive('showStack',function(frontloaded,$modal){
	return {
		scope:{
			stack:'=showStack'
		},
		link: function(scope, element, attributes, ngModel) {

			element.on('click',function () {

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

app.controller('ErrorsController',function($scope,httpi,frontloaded,language){
	
	httpi({
		method:'get'
		,url:'/api/buckets/:bucket_id/errors'
		,params:{
			bucket_id:frontloaded.bucket_id
		}
	}).success(function(errors){
		$scope.errors = errors
	})

})

app.factory('hapi',function($rootScope,httpi,frontloaded){
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

		if(frontloaded.csrfToken)
			cleanParams._token = frontloaded.csrfToken

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
  }
});


app.filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
});

app.filter('fileName', function() {
  return function(url) {
    var urlParts = url.split('/')
    return urlParts[urlParts.length-1]
  };
});

app.filter('withoutFileName', function() {
  return function(url) {
    var urlParts = url.split('/')
    urlParts.splice(urlParts.length-1,1)
  	return urlParts.join('/')
  };
});