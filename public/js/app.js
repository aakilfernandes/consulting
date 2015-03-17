var app = angular.module('app',[
	'isoform'
	,'frontloader'
	,'httpi'
	,'ui.bootstrap'
	,'yaru22.angular-timeago'
	,'angular-growl'
])
.config(configApp)
.run(runApp)
.controller('ProfileController',ProfileController)
.controller('MessagesController',MessageController)
.controller('JoinController',JoinController)
.controller('UserController',UserController)
.controller('PasswordController',PasswordController)
.directive('autofocus',autofocusDirective)

function configApp($provide,$compileProvider,$httpProvider,growlProvider,IsoformProvider){
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

}

function runApp($rootScope,$http,frontloaded,Isoform,growl) {

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
}

function UserController($scope,httpi,frontloaded,growl){
	angular.extend($scope,frontloaded.me)

	$scope.update = function(){
		httpi({
  			method:'POST'
  			,data:$scope.isoform.values
  			,url:'/api/user'
  		}).success(function(user){
  			growl.add('success','Settings updated')
  		}).error(function(response,code){
			$scope.isoform.messages = response
		})
	}
}

function MessageController($scope,$http){
	
	$scope.messages = []
	$scope.isLoading = true

	var page = 1

	$scope.loadMore = function(){
		$scope.isLoading = true
		$http({
			method:'GET'
			,url:'/api/messages'
			,params:{
				page:page
			}
		}).success(function(response){
			$scope.isLoading = false
			$scope.response = response
			$scope.messages = $scope.messages.concat(response.data)
			page++
		})
	}

	$scope.loadMore()
}

function JoinController($scope,$timeout){	
	if($scope.isAvailable===undefined)
		$scope.isAvailable = true
	
	if($scope.isNotifiedOfRequests===undefined)
		$scope.isNotifiedOfRequests = true

	if($scope.usesGravatar===undefined)
		$scope.usesGravatar = true
}

function PasswordController(){}

function ProfileController($scope,$modal,httpi,frontloaded){

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

	$scope.bumpProject = function(project,direction){

		httpi({
  			method:'POST'
  			,data:{id:project.id,direction:direction}
  			,url:'/api/projects/:id/bump'
  		}).success(function(projects){
  			$scope.user.projects = projects
  		})
	}

}

function MessagesController($scope,$http){
	
	$scope.messages = []
	$scope.isLoading = true

	var page = 1

	$scope.loadMore = function(){
		$scope.isLoading = true
		$http({
			method:'GET'
			,url:'/api/messages'
			,params:{
				page:page
			}
		}).success(function(response){
			$scope.isLoading = false
			$scope.response = response
			$scope.messages = $scope.messages.concat(response.data)
			page++
		})
	}

	$scope.loadMore()
}

function MessageModalController($scope,$http,$modalInstance,user,growl){

	$scope.user = user

	$scope.cancel = function(){
  		$modalInstance.dismiss('cancel');
  	}

  	$scope.submit = function(){

  		$http({
  			method:'POST'
  			,data:$scope.isoform.values
  			,url:'/api/user/'+user.id+'/messages'
  		}).success(function(){
  			$modalInstance.close()
  			growl.add('success','Message sent!')
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

function autofocusDirective($timeout) {
  	return {
    	restrict: 'A',
	    link : function($scope, $element) {
	      	$timeout(function() {
	       		$element[0].focus();
	      	});
	    }
  	}
}

