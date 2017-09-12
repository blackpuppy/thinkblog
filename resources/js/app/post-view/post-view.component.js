'use strict';

angular.module('postView')
.component('postView', {
    templateUrl: 'Public/template/post-view/post-view.template.html',
    controller: ['$http', '$routeParams', '$log',
        function PostViewController($http, $routeParams, $log) {
            this.postId = $routeParams.postId;

            var self = this;

            $http.get('api/posts/' + $routeParams.postId).then(function(response) {
                $log.info('response = ', response);
                self.post = response.data.post;
            });
        }
    ]
});
