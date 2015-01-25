var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'angulytics'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'simpleStorage'
])

app.config(function(angulyticsProvider,$provide){
	angulyticsProvider.$get = function(){
		this.endpoint = 'http://localhost:8000/endpoints/1'
		this.key = '8f33fdc9de2e520c42f6cc5b'
		return this
	}

	$provide.decorator('$http',function($delegate,frontloaded){
		$delegate.defaults.transformRequest.push(function(dataJson){
			if(!dataJson) return
			var data = angular.fromJson(dataJson)
			data._token = frontloaded.csrfToken
			return angular.toJson(data)
		})
		return $delegate
	})
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

		hapi('put','/api/buckets/:id',bucket).success(function(bucket){
			$scope.buckets[index] = bucket
		})
	}

})

app.controller('ProfilesController',function($scope,httpi,frontloaded,language){
	$scope.profiles = []
	$scope.statuses = frontloaded.statuses
	$scope.bucket_id = frontloaded.bucket_id
	
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

app.directive('profile',function(httpi,frontloaded,urlJson,$local){

	return {
		link:function(scope,element,attributes){

			scope.profile = scope.$eval(attributes.profile)

			scope.viewErrors = function(){
				$local.set('errorsFilters',{profile_id:scope.profile.id})
			}

			scope.$watch('profile',function(profile,oldProfile){
				if(angular.equals(profile,oldProfile)) return

				if(profile.status_id != oldProfile.status_id){
					changeIsCollapsed(profile)
					return
				}

				httpi({
					method:'PUT'
					,url:'/api/buckets/:bucket_id/profiles/:id'
					,data:angular.copy(profile)
				})

				function changeIsCollapsed(profile){
					if(['closed','ignored'].indexOf(profile.status_id)!==-1){
						profile.isCollapsed = true
					}else{
						profile.isCollapsed = false
					}
				}

			},true)

		}
	}
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

app.controller('ErrorsController',function($scope,httpi,frontloaded,$local){
	
	$scope.errorsFiltersOptions = frontloaded.errorsFiltersOptions
	$scope.errorsFilters = $local.get('errorsFilters')

	Object.keys($scope.errorsFiltersOptions).forEach(function(filter){
		var options = $scope.errorsFiltersOptions[filter]
		options.forEach(function(option){
			if(!option.id && option.value) option.id = option.value
			if(!option.value && option.alias) option.value = option.alias
		})
	})

	addDefaultOption('profiles','All Profiles')
	addDefaultOption('browsers','All Browsers')
	addDefaultOption('oses','All Operating Systems')
	addDefaultOption('devices','All Devices')

	loadErrors()

	$scope.$watch('errorsFilters',function(value,oldValue){
		console.log('watch',value,oldValue)
		if(angular.equals(value,oldValue)) return
		$local.set('errorsFilters',value)
		loadErrors()
	},true)

	function loadErrors(){
		var params = $scope.errorsFilters ? $scope.errorsFilters : {}
		params.bucket_id = frontloaded.bucket_id

		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/errors'
			,params:params
		}).success(function(errors){
			$scope.errors = errors
		})
	}

	function addDefaultOption(filter,value){
		$scope.errorsFiltersOptions[filter].unshift({
			id:undefined
			,value:value
		})
	}

})


app.directive('timestamp',function(){
	return {
		scope:{timestamp:'=timestamp'}
		,templateUrl:'/templates/timestamp.html'
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

app.filter('localTime', function($filter) {
  return function(timestamp) {
  	var date = new Date(timestamp+' +00')
  	return $filter('date')(date, 'MMM d, y')+' at '+$filter('date')(date, 'h:mm:ss a')
  };
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