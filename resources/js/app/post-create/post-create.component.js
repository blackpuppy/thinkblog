'use strict';

angular.module('postCreate')
.component('postCreate', {
    templateUrl: 'Public/template/post-create/post-create.template.html',
    controller: [
        '$stateParams', 'Post', '$state', '$log',
        function PostCreateController($stateParams, Post, $state, $log) {
            var self = this;
            this.save = function(isValid) {
                $log.info('form submitted: isValid = ', isValid);

                self.submitted = true;
                if (isValid) {
                    Post.save(self.data.post, function() {
                        $log.info('post is created!');
                        $state.go('post-list');
                    }, function() {
                        $log.info('post is not created.');
                    });
                }
            }
        }
    ]
});
