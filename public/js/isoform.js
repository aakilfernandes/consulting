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
							if(angular.equals(value,oldValue)) return
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


	isoformModule.directive('isoformField',function($http){
		return {
			require:'ngModel'
			,link:function(scope,element,attributes,ngModel){
				var isoform = scope.isoform
					,field = attributes['isoformField']
					,rules = isoform.fields[field]
					,value = scope[attributes.ngModel] = isoform.values[field]

				scope.$watch(attributes.ngModel,function(value,oldValue){
					if(value==oldValue) return
					if(scope.isoform.shouldClearMessagesOnChange)
						scope.isoform.messages[field] = []
					isoform.values[field] = scope.$eval(attributes.ngModel)
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
			
			//where is your ajax validation endpoint
			this.url = '/isoform'

			//amount of time to wait before sending an ajax validation request
			this.throttle = 200
			
			//should isoform clear the current validation messages as soon as the input changes?
			this.shouldClearMessagesOnChange = true


			this.scope = scope			
			this.namespace = isoformSeed.namespace
			this.fields = isoformSeed.fields
			this.values = isoformSeed.values
			this.messages = isoformSeed.messages
			this.request = null
			this.timeout = null
			this.response = null
		}

		Isoform.prototype.applyIfExists = function(functionName,arguments){
			if(this[functionName])
				return this[functionName].apply(this,arguments)
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

				if(isoform.applyIfExists('doBeforeAjaxValidation') === false)
					return

				$http({
				    url: '/isoform'
				    ,method: "GET"
				    ,params: {namespace:isoform.namespace,values:values}
				 	,timeout: isoform.request
				}).then(httpHandler,httpHandler);

				function httpHandler(response){
					if(!isoform.applyIfExists('doAfterAjaxValidation',[response]))
						return false

					isoform.messages = response.data
				}

				isoform.timeout = null
			
			},this.throttle)

			return $http;
		}

		return Isoform

	})

}())
