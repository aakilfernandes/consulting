var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'angulytics'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'simpleStorage'
])

app.config(function(angulyticsProvider,$provide,$compileProvider){
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

app.run(function($rootScope,frontloaded) {
	$rootScope._ = _
	$rootScope.frontloaded = frontloaded
});



app.controller('BucketsController',function($scope,hapi,language){
	$scope.new = function(){
		var name = window.prompt(language.bucketName);
		if(name===null) return

		hapi('POST','/api/buckets',{name:name})
			.success(function(bucket){
				$scope.frontloaded.buckets.push(bucket)
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

app.controller('ProfilesController',function($scope,httpi,$local){
	$scope.profiles = []
	
	$scope.statusFilters = [
		new StatusFilter('Any Status',undefined)
		,new StatusFilter('Only Open Profiles',['default','low','medium','high','critical'])
	]

	$scope.frontloaded.statuses.forEach(function(status){
		$scope.statusFilters.push(new StatusFilter(status.label,[status.id]))
	})

	$scope.params = {
		bucket_id: $scope.frontloaded.bucket.id
		,filters: $local.get('profilesFilters') ? $local.get('profilesFilters') : {
			status_id: $scope.statusFilters[1].status_ids
		}
		,sort:$local.get('profilesSort') ? $local.get('profilesSort') : undefined
	}

	$scope.sorts = [
		new Sort('Recently Seen','recentlySeen')
		,new Sort('Recently Created',undefined)
		,new Sort('Highest Priority','highestPriority')
	]

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		loadProfiles()
	},true)

	$scope.$watch('sorts',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		loadProfiles()
	},true)

	loadProfiles()

	function loadProfiles(){
		
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles'
			,params:angular.copy($scope.params)
		}).success(function(profiles){
			$scope.profiles = profiles
		})
	}

	function StatusFilter(label,status_ids){
		this.label = label
		this.status_ids = status_ids
	}

	function Sort(label,id){
		this.label = label
		this.id = id
	}
})

app.directive('profile',function(httpi,urlJson,$local){

	return {
		link:function(scope,element,attributes){

			scope.profile = scope.$eval(attributes.profile)

			scope.viewErrors = function(){
				$local.set('errorsFilters',{profile_id:scope.profile.id})
			}

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

	$scope.pages = []
	$scope.errors = []
	$scope.pageSize = 5

	var pagesVisible = 10

	$scope.params={
		page:0
		,pageSize:$scope.pageSize
		,bucket_id:$scope.frontloaded.bucket.id
		,profile_id:$scope.frontloaded.profile.id
		,filters:{}
	}
	
	loadErrors()

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		$local.set('errorsFilters',value.filters)
		loadErrors()
	},true)

	function loadErrors(){
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles/:profile_id/errors'
			,params:angular.copy($scope.params)
		}).success(function(response){
			$scope.errors = response.data
			$scope.errorsCount = response.total
		})
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