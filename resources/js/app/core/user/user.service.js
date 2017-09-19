'use strict';

angular.module('core.user')
.factory('User', ['$resource', '$log',
    function($resource, $log) {
        var service = {};

        service.Login = Login;
        service.SetCredentials = SetCredentials;
        service.ClearCredentials = ClearCredentials;

        return $resource(url, {}, {
            'login': {
                method: 'POST',
                params: {
                    username: '@username',
                    password: '@password'
                }
            },
            'signup': {
                method: 'POST',
                params: {
                    username: '@username',
                    password: '@password',
                    email: '@email'
                }
            },
            'logout': {
                method: 'POST'
            }
        });
    }
]);
