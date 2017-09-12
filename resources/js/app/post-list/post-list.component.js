'use strict';

angular.module('postList')
.component('postList', {
    templateUrl: 'Public/template/post-list/post-list.template.html',
    controller: ['Post', '$log',
        function PostListController(Post, $log) {
            var self = this;
            Post.query(ThinkBlog.getUrl(ThinkBlog.URL_API_SHOW_POST), function(response) {
                $log.info('Post.query() returns data: ', response);

                self.posts = response.data;
                self.params = response.queryParams;
            });
        }
    ]
});
