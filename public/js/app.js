var app = angular.module('app',['isoform','frontloader','httpi','angulytics'])

/*
app.config(function(angulyticsProvider){
	angulyticsProvider.$get = function(){
		this.endpoint = 'http://localhost:8000/endpoints/1'
		this.key = '8f33fdc9de2e520c42f6cc5b'
		console.log(this)
		return this
	}
})
*/

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