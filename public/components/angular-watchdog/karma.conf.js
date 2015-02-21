// Karma configuration
// Generated on Fri Feb 13 2015 13:08:01 GMT-0500 (EST)

var dotenv = require('dotenv');
dotenv.load();

module.exports = function(config) {

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

    config.set({
        basePath: '',
        frameworks: ['mocha', 'sinon-chai'],
        files: [
            'node_modules/angular/angular.js'
            ,'node_modules/angular-mocks/angular-mocks.js'
            ,'dist/angular-watchdog.min.js'
            ,'test/unit/spec.js'
        ],
        exclude: [],
        preprocessors: {},
        reporters: ['dots', 'saucelabs'],
        startConnect: true,
        port: 9876,
        colors: true,
        logLevel: config.LOG_INFO,
        autoWatch: true,
        browsers: ['Chrome'],
        sauceLabs: {
            testName: 'angular-watchdog unit'
            ,verbose: true
        },
        browserDisconnectTimeout: 60 * 1000,
        browserDisconnectTolerance: 2,
        browserNoActivityTimeout: 60 * 1000,
        captureTimeout: 60 * 1000,
        customLaunchers: customLaunchers,
        browsers: Object.keys(customLaunchers),
        reporters: ['dots', 'saucelabs'],
        singleRun: true
    });

    if (process.env.TRAVIS) {
        var buildLabel = 'TRAVIS #' + process.env.TRAVIS_BUILD_NUMBER + ' (' + process.env.TRAVIS_BUILD_ID + ')';

        config.logLevel = config.LOG_INFO;
        config.browserNoActivityTimeout = 120000;

        config.sauceLabs.build = buildLabel;
        config.sauceLabs.startConnect = false;
        config.sauceLabs.tunnelIdentifier = process.env.TRAVIS_JOB_NUMBER;
        config.sauceLabs.recordScreenshots = true;

        if (process.env.BROWSER_PROVIDER === 'saucelabs' || !process.env.BROWSER_PROVIDER) {
          config.captureTimeout = 0;
        }
  }

};
