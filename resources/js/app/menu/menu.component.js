'use strict';

angular.module('menu')
.component('menu', {
    templateUrl: 'Public/template/menu/menu.template.html',
    controller: ['Auth', '$rootScope', '$state', '$log',
        function(Auth, $rootScope, $state, $log) {
            this.homeUrl = ThinkBlog.getUrl(ThinkBlog.URL_HOME_PAGE);
            this.postListUrl = ThinkBlog.getUrl(ThinkBlog.URL_POST_LIST);
            this.angularjsUrl = ThinkBlog.getUrl(ThinkBlog.URL_ANGULARJS);

            this.getFullName = function(user) {
                if (user) {
                    return user.profile.first_name + ' ' + user.profile.last_name;
                }
                return null;
            }

            this.logOut = function() {
                Auth.ClearCredentials();
                $state.go('home');
            }
        }
    ]
});
