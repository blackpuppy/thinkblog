'use strict';

angular.module('profileView')
.component('profileView', {
    bindings: {
        data: '<',
        genders: '<'
    },
    templateUrl: 'Public/template/profile-view/profile-view.template.html'
});
