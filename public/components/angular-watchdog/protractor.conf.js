var config = {
  baseUrl: 'http://localhost:9001/',
  framework:'mocha',
  specs: ['test/e2e/spec.js'],
  capabilities: {browserName: 'chrome'}
};

var customLaunchers = {
    'SL_Chrome': {
        base: 'SauceLabs',
        browserName: 'chrome',
        version: '39'
    },
    'SL_Firefox': {
        base: 'SauceLabs',
        browserName: 'firefox',
        version: '31'
    },
    'SL_Safari': {
        base: 'SauceLabs',
        browserName: 'safari',
        platform: 'OS X 10.10',
        version: '8'
    },
    'SL_IE_9': {
        base: 'SauceLabs',
        browserName: 'internet explorer',
        platform: 'Windows 2008',
        version: '9'
    },
    'SL_IE_10': {
        base: 'SauceLabs',
        browserName: 'internet explorer',
        platform: 'Windows 2012',
        version: '10'
    },
    'SL_IE_11': {
        base: 'SauceLabs',
        browserName: 'internet explorer',
        platform: 'Windows 8.1',
        version: '11'
    }
};


if (process.env.TRAVIS_BUILD_NUMBER) {
	
	config.sauceUser = process.env.SAUCE_USERNAME;
	config.sauceKey = process.env.SAUCE_ACCESS_KEY;
	

	config.multiCapabilities = Object.keys(customLaunchers).map(function(id){
		var capabilities =  customLaunchers[id]

		capabilities['tunnel-identifier'] = process.env.TRAVIS_JOB_NUMBER,
		capabilities['build'] = process.env.TRAVIS_BUILD_NUMBER,
		capabilities['name'] = 'angular-watchdog e2e'
		return capabilities
	})
}


exports.config = config