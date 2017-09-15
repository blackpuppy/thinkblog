'use strict';

angular.module('thinkblogApp')
.config([
    '$stateProvider',
    '$urlRouterProvider',
    '$locationProvider',
    '$translateProvider',
    function config(
        $stateProvider,
        $urlRouterProvider,
        $locationProvider,
        $translateProvider
    ) {
        // $locationProvider.hashPrefix('!');

        $urlRouterProvider.otherwise('/posts');

        var postListState = {
            name: 'post-list',
            url: '/posts',
            component: 'postList',
            resolve: {
                data: function (Post) {
                    return Post.query();
                }
            }
        };

        var postViewState = {
            name: 'post-view',
            url: '/posts/{id}',
            component: 'postView',
            resolve: {
                data: function(Post, $transition$) {
                    return Post.get({id: $transition$.params().id});
                }
            }
        };

        $stateProvider.state(postListState);
        $stateProvider.state(postViewState);

        $translateProvider.useUrlLoader(ThinkBlog.getUrl(ThinkBlog.URL_API_TRANSLATE));
        $translateProvider.preferredLanguage('zh-CN');
    }
]);
