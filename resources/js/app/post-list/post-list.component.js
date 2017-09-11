'use strict';

angular.module('postList')
.component('postList', {
    templateUrl: '/Public/template/post-list.template.html',
    controller: function PostListController() {
        this.posts = [
            {
                name: 'Nexus S',
                snippet: 'Fast just got faster with Nexus S.'
            }, {
                name: 'Motorola XOOM™ with Wi-Fi',
                snippet: 'The Next, Next Generation tablet.'
            }, {
                name: 'MOTOROLA XOOM™',
                snippet: 'The Next, Next Generation tablet.'
            }
        ];
    }
});
