'use strict';

angular.module('core.user')
.factory('User', ['$http', '$log',
    function($http, $log) {
        var service = {};

        service.Signup = Signup;

        return service;

        function Signup(username, password, email, callback) {
            var url = ThinkBlog.getUrl(ThinkBlog.URL_API_SIGNUP);
            $http.post(url, { name: username, password: password, email: email })
                .then(function (response) {
                    callback(response);
                }, function (response) {
                    // callback(response);
                });
        }

        // return $resource(url, {}, {
        //     'login': {
        //         method: 'POST',
        //         params: {
        //             username: '@username',
        //             password: '@password'
        //         }
        //     },
        //     'signup': {
        //         method: 'POST',
        //         params: {
        //             username: '@username',
        //             password: '@password',
        //             email: '@email'
        //         }
        //     }
        // });
    }
]);
