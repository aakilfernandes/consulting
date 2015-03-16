var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'angular-growl'
])

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
	$rootScope._ = _

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
		return

		if(this.isGrowlSupressed)
			return

		growl.add('info','Validating inputs')
	}

	Isoform.prototype.doAfterAjaxValidation = function(response){
		return

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
	
	if($scope.isAvailable===undefined)
		$scope.isAvailable = true
	
	if($scope.isNotifiedOfRequests===undefined)
		$scope.isNotifiedOfRequests = true

	if($scope.usesGravatar===undefined)
		$scope.usesGravatar = true
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

app.controller('ProfileController',function($scope,$modal,httpi,frontloaded){

	$scope.user = angular.copy(frontloaded.user)

	$scope.openSkillModal = function(skill){
		$modal.open({
	      templateUrl: '/angular/templates/skillModal',
	      size: 'md',
	      resolve: {
	        skill:function(){
	        	return skill
	        }
	      },controller:SkillModalController
	    }).result.then(function(skills){
	    	if(skills)
	    		$scope.user.skills = skills
	    })
	}

	$scope.deleteSkill = function(skill,index){
		$scope.user.skills.splice(index,1)

		httpi({
  			method:'DELETE'
  			,data:skill
  			,url:'/api/skills/:id'
  		})
	}

	$scope.openProjectModal = function(project){
		$modal.open({
	      templateUrl: '/angular/templates/projectModal',
	      size: 'md',
	      resolve: {
	    	project:function(){
	    		return project
	    	}
	      },controller:ProjectModalController
	    }).result.then(function(projects){
	    	if(projects)
	    		$scope.user.projects = projects
	    })
	}

	$scope.deleteProject = function(project,index){
		$scope.user.projects.splice(index,1)

		httpi({
  			method:'DELETE'
  			,data:project
  			,url:'/api/projects/:id'
  		})
	}

	$scope.openMessageModal = function(){
		$modal.open({
	      templateUrl: '/angular/templates/messageModal',
	      size: 'md',
	      resolve: {
	    	user:function(){
	    		return $scope.user
	    	}
	      },controller:MessageModalController
	    }).result.then()
	}

})

function MessageModalController($scope,$http,$modalInstance,user){

	$scope.user = user

	$scope.cancel = function(){
  		$modalInstance.dismiss('cancel');
  	}

  	$scope.submit = function(){
  		
  		$http({
  			method:'POST'
  			,data:$scope.isoform.values
  			,url:'/api/user/'+user.id+'/messages'
  		}).success(function(skills){
  			$modalInstance.close(skills)
  		}).error(function(response,code){
			$scope.isoform.messages = response
		})
  	}
}

function SkillModalController($scope, $modalInstance, $http, skill){

	if(skill){
		$scope.skill = skill
		$scope.isEditing = true
		angular.extend($scope,skill)
		angular.extend($scope,skill.pivot)
	}else{
		$scope.level = 5
	}

  	$scope.cancel = function(){
  		$modalInstance.dismiss('cancel');
  	}

  	$scope.submit = function(){

  		if($scope.isEditing)
  			url = '/api/skills/'+skill.pivot.skill_id
  		else
  			url = '/api/skills/'

  		$http({
  			method:'POST'
  			,data:$scope.isoform.values
  			,url:url
  		}).success(function(skills){
  			$modalInstance.close(skills)
  		}).error(function(response,code){
			$scope.isoform.messages = response
		})
  	}

}

function ProjectModalController($scope, $modalInstance, $http, project){

	if(project){
		$scope.project = project
		$scope.isEditing = true
		angular.extend($scope,project)
		var url = '/api/projects/'+project.id
	}else{
		$scope.level = 5
		var url = '/api/projects'
	}

  	$scope.cancel = function(){
  		$modalInstance.dismiss('cancel');
  	}

  	$scope.submit = function(){

  		$http({
  			method:'POST'
  			,data:$scope.isoform.values
  			,url:url
  		}).success(function(projects){
			$modalInstance.close(projects)
  		}).error(function(response,code){
			$scope.isoform.messages = response
		})
  	}

}

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