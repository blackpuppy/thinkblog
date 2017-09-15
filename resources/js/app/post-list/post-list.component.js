'use strict';

angular.module('postList')
.component('postList', {
    bindings: { data: '<' },
    templateUrl: 'Public/template/post-list/post-list.template.html',
    controller: [
    	'$scope',
    	'Post',
    	'$log',
    	function($scope, Post, $log) {
    		var self = this;
    		this.pageChange = function() {
    			// $log.info('self.data.queryParams.page = ', self.data.queryParams.page);
    			var parameters = self.data.parameters;
    			parameters.page = self.data.queryParams.page;
    			Post.query(parameters, function(response) {
	    			// $log.info('pageChange() success: response = ', response);
    				self.data = response;
    			});
    		};
    	}
    ]
});
