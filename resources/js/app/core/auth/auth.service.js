'use strict';

angular.module('core.auth')
.factory('Auth', ['$http', '$cookies', '$rootScope', 'jwtHelper', '$log',
    function($http, $cookies, $rootScope, jwtHelper, $log) {
        var service = {};

        service.Login = Login;
        service.SetCredentials = SetCredentials;
        service.ClearCredentials = ClearCredentials;

        return service;

        function Login(username, password, callback) {
            var url = ThinkBlog.getUrl(ThinkBlog.URL_API_LOGIN);
            $http.post(url, { name: username, password: password })
                .then(function (response) {
                    callback(response);
                }, function (response) {
                    // callback(response);
                });
        }

        function SetCredentials(data) {
            $log.info('Auth.SetCredentials(): data = ', data);

            $rootScope.globals = {
                token: data.token,
                currentUser: data.data.user
            };

            $log.info('Auth.SetCredentials(): $rootScope.globals = ', $rootScope.globals);

            // set default auth header for http requests
            $http.defaults.headers.common['Authorization'] = 'Bearer ' + data.token;

            // store user details in globals cookie that keeps user logged in for 1 week (or until they logout)
            var cookieExp = new Date();
            cookieExp.setDate(cookieExp.getDate() + 7);
            $cookies.putObject('globals', $rootScope.globals, { expires: cookieExp });
        }

        function ClearCredentials() {
            $rootScope.globals = {};
            $cookies.remove('globals');
            $http.defaults.headers.common.Authorization = 'Bearer';
        }
    }
]);
