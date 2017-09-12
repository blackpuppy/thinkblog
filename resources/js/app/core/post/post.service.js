'use strict';

angular.module('core.post')
.factory('Post', ['$resource',
    function($resource) {
    	var url = ThinkBlog.getUrl(ThinkBlog.URL_API_UPDATE_POST);

        return $resource(url, {}, {
            query: {
                method: 'GET',
                params: {id: 'posts'}
            }
        });
    }
]);
