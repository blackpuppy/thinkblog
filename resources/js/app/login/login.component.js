'use strict';

angular.module('login')
.component('login', {
    templateUrl: 'Public/template/login/login.template.html',
    controller: [
        '$location',
        'Auth',
        'Flash',
        '$log',
        function LoginController($location, Auth, Flash, $log) {
            var self = this;

            self.login = login;

            (function initController() {
                // reset login status
                Auth.ClearCredentials();
            })();

            function login() {
                self.dataLoading = true;
                Auth.Login(self.username, self.password, function (response) {
                    $log.info('login() response = ', response);

                    if (response.status === 200) {
                        Auth.SetCredentials(response.data);
                        $location.path('/');
                    } else {
                        Flash.Error(response.data.meta.message);
                        self.dataLoading = false;
                    }
                });
            };
        }
    ]
});
