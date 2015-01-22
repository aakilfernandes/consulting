(function(){

	var isoformModule = angular.module('isoform', [])

	isoformModule.directive('isoform',function(Isoform){
		return {
			compile:function(){
				return {
					pre:function(scope,element,attributes){
						var isoformSeed = JSON.parse(attributes.isoform)
						scope.isoform = new Isoform(scope,isoformSeed)
						scope.$watch('isoform.values',function(value,oldValue){
							if(value==oldValue) return
							scope.isoform.validate()
						},true)
					}
				}
			}
		}
	})

	isoformModule.directive('isoformMessages',function(){
		return {
			scope:true
			,link:function(scope,element,attributes){
				scope.isoformMessages=[]
				scope.$watch('isoform.messages',function(messages){
					if(messages[attributes.isoformMessages])
						scope.isoformMessages = messages[attributes.isoformMessages]
				})

			}
		}
	})


	isoformModule.directive('isoformValidate',function($http){
		return {
			require:'ngModel'
			,link:function(scope,element,attributes,ngModel){
				var isoform = scope.isoform
					,field = attributes['isoformValidate']
					,rules = isoform.fields[field]
					,value = scope[attributes.ngModel] = isoform.values[field]


				scope.$watch(attributes.ngModel,function(value,oldValue){
					if(value==oldValue) return
					isoform.values[field] = value = scope[attributes.ngModel]
				},true)

				scope.$watch('isoform.response',function(value,oldValue){
					if(value==oldValue) return
					if( !scope.isoform.response
						|| !scope.isoform.response.values[field]
						|| value!=scope.isoform.response.values[field]) return
					if(scope.isoform.response.messages[field].length>0)
						ngModel.$setValidity('isoform',false)
					else
						ngModel.$setValidity('isoform',true)
				})
			}
		}
	})

	isoformModule.factory('Isoform',function($http,$q,$timeout,$rootScope){

		var Isoform = function(scope,isoformSeed){
			this.url = '/isoform'
			this.scope = scope
			this.fields = isoformSeed.fields
			this.values = isoformSeed.values
			this.messages = isoformSeed.messages
			this.request = null
			this.timeout = null
			this.throttle = 500
			this.response = null

		}


		Isoform.prototype.validate = function(isSubmit){

			var fields = angular.copy(this.fields)
				,isoform = this


			if(!isSubmit){
				Object.keys(fields).forEach(function(field){
					if(!(field in isoform.values) || isoform.values[field]===undefined) delete fields[field]
				})
			}

			if(Object.keys(fields).length==0) return
			
			if(isoform.timeout) $timeout.cancel(isoform.timeout)

			isoform.timeout = $timeout(function(){

				if(isoform.request) isoform.request.resolve()
			
				isoform.request = $q.defer()

				var values = angular.copy(isoform.values)

				$http({
				    url: '/isoform'
				    ,method: "GET"
				    ,params: {values:values,fields:fields}
				 	,timeout: isoform.request
				}).then(httpHandler,httpHandler);

				function httpHandler(response){
					isoform.scope.isoform.messages = response.data
					isoform.scope.$broadcast('isoform.response',{
						values:values
						,messages:response.data
					})
				}

				isoform.timeout = null
			
			},this.throttle)

			return $http;
		}

		return Isoform

	})

}())
