#Angular Watchdog

An error monitoring module for angular apps

## Installation

1. `bower install angular-watchdog` or download the [source](https://github.com/angulytics/angular-watchdog/tree/master/dist)
2. Include angular-watchdog.js in your project
3. `app.module('myApp',['watchdog'])`

## Configuration

    app.config(function(watchdogProvider){
		watchdogProvider.options = {
			endpoint:'http://logging-server.com'
		}
	})
	
## Angulytics

[Angulytics](https://angulytics.com) provides a logging server, however Angular Watchdog is built to be server agnostic. You can set whatever endpoint you like.

## Browser Support [![Build Status](https://travis-ci.org/angulytics/angular-watchdog.svg?branch=master)](https://travis-ci.org/angulytics/angular-watchdog)
IE 9+, Chrome, Firefox, Safari
