'use strict';

angular.module('postEdit')
.component('postEdit', {
    bindings: { data: '<' },
    templateUrl: 'Public/template/post-edit/post-edit.template.html',
    controller: [
    	'$stateParams', '$log',
        function PostEditController($stateParams, $log) {
            this.id = $stateParams.id;

            var self = this;
            this.save = function(isValid) {
            	self.submitted = true;
            	if (isValid) {
            		$log.info('form is valid!');
            	}
            }
        }
    ]
});
