'use strict';

angular.module('signup')
.component('signup', {
    templateUrl: 'Public/template/signup/signup.template.html',
    controller: [
        '$state',
        'Auth',
        'User',
        'Flash',
        '$log',
        function SignupController($state, Auth, User, Flash, $log) {
            var self = this;

            self.signup = signup;

            (function initController() {
                // reset signup status
                Auth.ClearCredentials();
            })();

            function signup() {
                self.dataLoading = true;
                User.Signup(
                    self.username, self.password, self.email,
                    function (response) {
                        $log.info('signup() response = ', response);

                        if (response.status === 201) {
                            Flash.Success(response.data.meta.message);
                            Auth.SetCredentials(response.data);
                            $state.go('home');
                        } else {
                            Flash.Error(response.data.meta.message);
                            self.dataLoading = false;
                        }
                    }
                );
            };
        }
    ]
});
