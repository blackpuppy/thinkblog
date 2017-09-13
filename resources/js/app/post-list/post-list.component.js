'use strict';

angular.module('postList')
.component('postList', {
    bindings: { data: '<' },
    templateUrl: 'Public/template/post-list/post-list.template.html'
});
