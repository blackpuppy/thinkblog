'use strict';

angular.module('thinkblogApp')
.config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
      	$locationProvider.hashPrefix('!');

      	$routeProvider.
        	when('/posts', {
          		template: '<post-list></post-list>'
        	}).
        	when('/posts/:id', {
          		template: '<post-view></post-view>'
        	}).
        	otherwise('/posts');
    }
]);;
