'use strict';

angular.module('postList')
.component('postList', {
    templateUrl: 'Public/template/post-list/post-list.template.html',
    controller: ['$http', '$log',
        function PostListController($http, $log) {
            var self = this;
            $http.get(ThinkBlog.getUrl(ThinkBlog.URL_API_SHOW_POST)).then(function(response) {
                $log.info('/api/posts returns data: ', response);
                self.posts = response.data.data;
                self.params = response.data.queryParams;
            })
        }
    ]
});
