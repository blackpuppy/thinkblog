'use strict';

angular.module('thinkblogApp')
.config(['$stateProvider', '$urlRouterProvider',
    function config($stateProvider, $urlRouterProvider) {
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
    }
]);
