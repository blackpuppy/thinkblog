'use strict';

angular.module('core.profile')
.factory('Profile', ['$resource', '$log',
    function($resource, $log) {
    	var url = ThinkBlog.getUrl(ThinkBlog.URL_API_SHOW_PROFILE);

    	// $log.info('Profile: url = ', url);

        return $resource(url, {}, {
            update: {
                method: 'POST',
                params: {id: '@id'}
            }
        });
    }
]);
