'use strict';

angular.module('core.post')
.factory('Post', ['$resource', '$log',
    function($resource, $log) {
    	var url = ThinkBlog.getUrl(ThinkBlog.URL_API_UPDATE_POST);

    	$log.info('Post: url = ', url);

        return $resource(url, {}, {
            query: {
                method: 'GET',
                params: {id: '@id'}
            }
        });
    }
]);
