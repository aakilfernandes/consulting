var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'angulytics'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'simpleStorage'
	,'angular-underscore'
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
	$scope.profilesFilters = {}
	
	$scope.statusFilters = [
		new StatusFilter('Any Status',undefined)
		,new StatusFilter('Only Open Profiles',['default','low','medium','high','critical'])
	]
	$scope.statuses.forEach(function(status){
		$scope.statusFilters.push(new StatusFilter(status.label,[status.id]))
	})

	$scope.profilesSorts = [
		new ProfilesSort('Recently Seen','recentlySeen')
		,new ProfilesSort('Recently Created',undefined)
		,new ProfilesSort('Highest Priority','highestPriority')
	]

	$scope.$watch('profilesFilters',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		loadProfiles()
	},true)

	$scope.$watch('profilesSort',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		loadProfiles()
	},true)

	$scope.setIsCollapsed = function(isCollapsed){
		$scope.profiles.forEach(function(profile){
			profile.isCollapsed = isCollapsed
		})
	}

	loadProfiles()

	function loadProfiles(){
		
		var params = {
			bucket_id:frontloaded.bucket_id
			,filtersJson:angular.toJson($scope.profilesFilters)
			,sort:$scope.profilesSort
		}


		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/profiles'
			,params:params
		}).success(function(profiles){
			$scope.profiles = profiles
		})
	}

	function StatusFilter(label,status_ids){
		this.label = label
		this.status_ids = status_ids
	}

	function ProfilesSort(label,id){
		this.label = label
		this.id = id
	}
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

	$scope.pages = []
	$scope.errors = []

	var pagesVisible = 10

	$scope.params={
		page:0
		,take:10
		,bucket_id:frontloaded.bucket_id
		,filters:$local.get('errorsFilters')
	}
	
	$scope.errorsFiltersOptions = frontloaded.errorsFiltersOptions
	
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

	$scope.$watch('params',function(value,oldValue){
		if(angular.equals(value,oldValue)) return
		$local.set('errorsFilters',value)
		loadErrors()
	},true)

	$scope.$watch('errors',function(value,oldValue){
		if(angular.equals(value,oldValue)) return

		var pages = []
			,pageNumber = $scope.params.page
			,pagesVisibleParity = pagesVisible %2 ? 'even':'odd'
			,pagesFlankCount = Math.floor(pagesVisible/2)
			,pageStart = pageNumber < pagesVisible/2 ? 1 : pageNumber - pagesFlankCount

		for(var i = pageStart; i <= pagesVisible; i++)
			pages.push(new Page(i,i===pageNumber))

		
		if(i!=1)
			pages.unshift(new Page(''))

		function Page(label,isActive){
			this.label = label
			this.isActive = isActive
		}

		$scope.pages = pages
		
	})

	function loadErrors(){
		httpi({
			method:'get'
			,url:'/api/buckets/:bucket_id/errors'
			,params:angular.copy($scope.params)
		}).success(function(response){
			$scope.errors = response.data
			$scope.errorsCount = response.total
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