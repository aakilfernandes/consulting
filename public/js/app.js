var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'angulytics'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'simpleStorage'
])

app.config(function(angulyticsProvider,$provide,$compileProvider,$httpProvider){
	angulyticsProvider.$get = function(){
		this.endpoint = 'http://localhost:8000/endpoints/1'
		this.key = '8f33fdc9de2e520c42f6cc5b'
		return this
	}

	$provide.decorator('$http',function($delegate,frontloaded){
		$delegate.defaults.transformRequest.push(function(dataJson){
			var data = dataJson ? angular.fromJson(dataJson) : {}
			data._token = frontloaded.csrfToken
			return angular.toJson(data)
		})

		return $delegate
	})
	
})

app.run(function($rootScope,$http,frontloaded) {
	$rootScope._ = _
	$rootScope._ = angular
	$rootScope.frontloaded = frontloaded
	
	$http.defaults.headers.delete = { 'Content-Type' : 'application/json' };
});



app.controller('BucketsController',function($scope,httpi,language){

	httpi({
		method:'GET'
		,url:'/api/buckets'
	}).success(function(buckets){
		$scope.buckets = buckets
	})

	$scope.new = function(){
		var name = window.prompt(language.bucketName);
		if(name===null) return

		$scope.isLoading = true
		httpi({
			method:'POST'
			,url:'/api/buckets'
			,data:{name:name}
		}).success(function(bucket){
			$scope.buckets.unshift(bucket)
			$scope.isLoading = false
		})
	}

	$scope.editName = function(bucket,index){
		var name = window.prompt(language.bucketName)
		if(name===null) return

		bucket.name = name

		httpi({
			method:'PUT'
			,url:'/api/buckets/:id'
			,data:bucket
		}).success(function(bucket){
			$scope.bucket = bucket
		})
	}

	$scope.delete = function(bucket,index){
		if(!confirm(language.bucketDelete)) return
		httpi({
			method:'DELETE'
			,url:'/api/buckets/:id'
			,data:bucket
		})
		$scope.buckets.splice(index,1)
	}

})

app.controller('ProfilesController',function($scope,httpi,$local){
	$scope.profiles = []
	
	$scope.statusFilters = angular.copy($scope.frontloaded.statuses)
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
			status_id: $local.get('profilesStatusFilter') ?
				$local.get('profilesStatusFilter') : 'default'
		}
		,sort:$local.get('profilesSort') ? $local.get('profilesSort') : 'recentlyCreated'
		,pageSize:5
	}

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		$local.set('profilesStatusFilter',$scope.params.filters.status_id)
		$local.set('profilesSort',$scope.params.sort)
		loadProfiles()
	},true)


	loadProfiles()

	function loadProfiles(){
		$scope.isLoading = true
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles'
			,params:angular.copy($scope.params)
		}).success(function(response){
			$scope.response = response
			$scope.profiles = response.data
			$scope.isLoading = false
		})
	}

})

app.directive('profile',function(httpi){

	return {
		scope:{
			profile:'=profile'
			,params:'=params'
		},link:function(scope,element,attributes){

			scope.$watch('profile',function(profile,oldProfile){

				if(angular.equals(profile,oldProfile)) return

				httpi({
					method:'PUT'
					,url:'/api/buckets/:bucket_id/profiles/:id'
					,data:angular.copy(profile)
				})

			},true)

		}
	}
})

app.directive('showStack',function($modal){
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

app.controller('ErrorsController',function($scope,httpi,$local){

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
  	,bucketDelete:'Are you sure? Delting buckets will also delete the data associated with them'
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
			var timeout
			scope.$watch('isShowing',function(value){
				

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