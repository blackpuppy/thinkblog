'use strict';

angular.module('postEdit')
.component('postEdit', {
    bindings: { data: '<' },
    templateUrl: 'Public/template/post-edit/post-edit.template.html',
    controller: [
        '$stateParams', 'Post', '$state', '$log',
        function PostEditController($stateParams, Post, $state, $log) {
            this.id = $stateParams.id;

            var self = this;
            this.save = function(isValid) {
                self.submitted = true;
                if (isValid) {
                    $log.info('form is valid!');

                    Post.update(self.data.post, function() {
                        // $log.info('post is saved!');
                        $state.go('post-list');
                    }, function() {
                        // $log.info('post is not saved.');
                    });
                }
            }
        }
    ]
});
