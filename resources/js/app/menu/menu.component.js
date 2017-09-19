'use strict';

angular.module('menu')
.component('menu', {
    templateUrl: 'Public/template/menu/menu.template.html',
    controller: ['Auth', '$rootScope', '$log',
        function(Auth, $rootScope, $log) {
            this.homeUrl = ThinkBlog.getUrl(ThinkBlog.URL_HOME_PAGE);
            this.postListUrl = ThinkBlog.getUrl(ThinkBlog.URL_POST_LIST);
            this.angularjsUrl = ThinkBlog.getUrl(ThinkBlog.URL_ANGULARJS);

            this.isAuthenticated = Auth.isAuthenticated();

            $log.info('menu: this.isAuthenticated = ', this.isAuthenticated);

            if (this.isAuthenticated) {
                var user = Auth.getCurrentUser();

                $log.info('menu: user = ', user);

                var profile = user.profile;
                this.uesrDisplayName = profile.first_name + ' ' + profile.last_name;
            }
        }
    ]
});
