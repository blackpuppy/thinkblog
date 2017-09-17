'use strict';

angular.module('thinkblogApp')
.config([
    '$stateProvider',
    '$urlRouterProvider',
    '$locationProvider',
    '$translateProvider',
    '$httpProvider',
    'jwtOptionsProvider',
    function config(
        $stateProvider,
        $urlRouterProvider,
        $locationProvider,
        $translateProvider,
        $httpProvider,
        jwtOptionsProvider
    ) {
        $urlRouterProvider.otherwise('/');

        var rootState = {
            name: 'root',
            abstract: true,
            views: {
                header: {
                    name: 'menu',
                    component: 'menu'
                },
                footer: {
                    name: 'bottom',
                    component: 'bottom'
                }
            }
        };

        var homeState = {
            name: 'home',
            url: '/',
            views: {
                'content@': 'home'
            },
            parent: 'root'
        };

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

        $stateProvider.state(rootState);
        $stateProvider.state(homeState);
        $stateProvider.state(postListState);
        $stateProvider.state(postViewState);

        $translateProvider.useUrlLoader(ThinkBlog.getUrl(ThinkBlog.URL_API_TRANSLATE))
        $translateProvider.preferredLanguage('zh-CN');
        $translateProvider.determinePreferredLanguage();
        // $translateProvider.useCookieStorage();
        $translateProvider.useLocalStorage();

        // jwtOptionsProvider.config({
        //     tokenGetter: [function() {
        //         return localStorage.getItem('id_token');
        //     }]
        // });

        // $httpProvider.interceptors.push('jwtInterceptor');
    }
]);
