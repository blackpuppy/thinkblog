'use strict';

angular.module('login')
.component('login', {
    templateUrl: 'Public/template/login/login.template.html',
    controller: [
        '$state',
        'Auth',
        'Flash',
        '$log',
        function LoginController($state, Auth, Flash, $log) {
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
                        Flash.Success(response.data.meta.message);
                        Auth.SetCredentials(response.data);
                        $state.go('home');
                    } else {
                        Flash.Error(response.data.meta.message);
                        self.dataLoading = false;
                    }
                });
            };
        }
    ]
});
