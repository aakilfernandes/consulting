module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
        all: ['Gruntfile.js','test/**/spec.js']
        ,options:{
          asi:true
          ,laxcomma:true
          ,laxbreak:true
          ,"-W058": true
        }
    },uglify:{
      js: {
        options: {
          sourceMap: true,
        },
        files: {
          'dist/angular-watchdog.min.js': ['dist/angular-watchdog.js']
        }
      }
    },karma: {
      unit: {
        configFile: 'karma.conf.js'
      }
    },connect: {
      server: {
        options: {
          hostname: 'localhost',
          port: 9001
        }
      }
    },protractor: {
      options: {
        configFile: "protractor.conf.js",
        keepAlive: false,
        noColor: false,
        args: {}
      },target:{}
    }
  });

  //grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-protractor-runner');
  grunt.loadNpmTasks('grunt-serve');
  grunt.loadNpmTasks('grunt-karma');

  grunt.registerTask('test', ['jshint','uglify','karma','connect','protractor']);
};
