'use strict';

angular.module('core.post')
.factory('Post', ['$resource', '$log',
    function($resource, $log) {
    	var url = ThinkBlog.getUrl(ThinkBlog.URL_API_UPDATE_POST);

    	$log.info('Post: url = ', url);

        return $resource(url, {id: '@id'}, {
            query: {
                method: 'GET',
                params: {
                    filter: null,
                    order: null,
                    pageSize: null,
                    page: null
                }
            },
            update: {
                method: 'POST',
                params: {id: '@id'}
            }
        });
    }
]);
