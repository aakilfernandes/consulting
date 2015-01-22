(function(){


	var frontloader = angular.module('frontloader',[])

	frontloader.directive('frontload',function(frontloaded){
		return {
			scope:{
				id:'@frontload'
				,type:'@frontloadType'
			}
			,link: function(scope, element, attrs, modelCtrl) {

				var value = element[0].value
				switch(scope.type){
					case 'json':
						frontloaded[scope.id] = JSON.parse(value)
						break;
					case 'string':
					default:
						frontloaded[scope.id] = value;
						break;
				}
		    }
		}
	})

	frontloader.factory('frontloaded',function(){ return {} })

}())