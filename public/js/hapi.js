app.factory('hapi',function(httpi,frontloaded,status){
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

		if(frontloaded.csrf)
			cleanParams._token = frontloaded.csrfToken

		var promise = 
			httpi({
			    method: method,
			    url: url,
			    params: cleanParams
			})
			.error(function(message){
				if(typeof(message)=='string')
					return alert('Error: '+message)

				if(error) error()
			})

		promise.withBlocker = function(){
			status.isBlocker = true
			promise.finally(function(){
				status.isBlocker = false
			})
			return promise
		}

		return promise
	}
})