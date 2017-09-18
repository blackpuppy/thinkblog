'use strict';

angular.module('menu')
.component('menu', {
    templateUrl: 'Public/template/menu/menu.template.html',
    controller: [//'jwtHelper',
        // function(jwtHelper) {
        function() {
            this.homeUrl = ThinkBlog.getUrl(ThinkBlog.URL_HOME_PAGE);
            this.postListUrl = ThinkBlog.getUrl(ThinkBlog.URL_POST_LIST);
            this.angularjsUrl = ThinkBlog.getUrl(ThinkBlog.URL_ANGULARJS);

            // this.tokenExpired = jwtHelper.isTokenExpired(expToken);
        }
    ]
});
