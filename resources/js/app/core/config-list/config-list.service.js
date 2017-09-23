'use strict';

angular.module('core.configList')
.factory('ConfigList', ['$resource', '$log',
    function($resource, $log) {
    	var url = ThinkBlog.getUrl(ThinkBlog.URL_API_CONFIG_LIST);

    	$log.info('ConfigList: url = ', url);

        return $resource(url, {}, {
            update: {
                method: 'POST',
                params: {id: '@id'}
            }
        });
    }
]);
