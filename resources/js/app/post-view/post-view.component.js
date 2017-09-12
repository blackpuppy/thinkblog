'use strict';

angular.module('postView')
.component('postView', {
    templateUrl: 'Public/template/post-view/post-view.template.html',
    controller: ['$routeParams', 'Post', '$log',
        function PostViewController($routeParams, Post, $log) {
            this.id = $routeParams.id;

            var self = this;

            Post.get({id: $routeParams.id}, function(response) {
                $log.info('Post.get(' + $routeParams.id + ') returns response = ', response);

                self.post = response.post;
            });
        }
    ]
});
