'use strict';

angular.module('profileEdit')
.component('profileEdit', {
    bindings: {
        data: '<',
        genders: '<'
    },
    templateUrl: 'Public/template/profile-edit/profile-edit.template.html',
    controller: [
        '$stateParams', 'Profile', '$state', '$log',
        function ProfileEditController($stateParams, Profile, $state, $log) {
            this.id = $stateParams.id;

            var self = this;
            this.save = function(isValid) {
                self.submitted = true;
                if (isValid) {
                    $log.info('form is valid!  self.data = ', self.data);

                    Profile.update({user: self.data.user}, function() {
                        $log.info('user profile is saved!');
                        $state.go('post-list');
                    }, function() {
                        $log.info('user profile is not saved.');
                    });
                }
            }
        }
    ]
});
