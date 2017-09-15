'use strict';

angular.module('postView')
.component('postView', {
    bindings: { data: '<' },
    templateUrl: 'Public/template/post-view/post-view.template.html',
    controller: ['$stateParams',
        function PostViewController($stateParams) {
            this.id = $stateParams.id;
        }
    ]
});
