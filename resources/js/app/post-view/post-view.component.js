'use strict';

angular.module('postView')
.component('postView', {
    template: 'TBD: View view for <span>{{$ctrl.postId}}</span>',
    controller: ['$routeParams',
        function PostViewController($routeParams) {
            this.postId = $routeParams.postId;
        }
    ]
});
