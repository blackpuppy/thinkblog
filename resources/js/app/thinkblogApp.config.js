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
            parent: 'root',
            views: {
                'content@': 'home'
            }
        };

        var loginState = {
            name: 'login',
            url: '/login',
            parent: 'root',
            views: {
                'content@': 'login'
            }
        };

        var postListState = {
            name: 'post-list',
            url: '/posts',
            parent: 'root',
            views: {
                'content@': 'postList'
            },
            resolve: {
                data: function (Post) {
                    return Post.query();
                }
            }
        };

        var postViewState = {
            name: 'post-view',
            url: '/posts/{id}',
            parent: 'root',
            views: {
                'content@': 'postView'
            },
            resolve: {
                data: function(Post, $transition$) {
                    return Post.get({id: $transition$.params().id});
                }
            }
        };

        $stateProvider.state(rootState);
        $stateProvider.state(homeState);
        $stateProvider.state(loginState);
        $stateProvider.state(postListState);
        $stateProvider.state(postViewState);

        $translateProvider.useUrlLoader(ThinkBlog.getUrl(ThinkBlog.URL_API_TRANSLATE))
        $translateProvider.preferredLanguage('zh-CN');
        $translateProvider.determinePreferredLanguage();
        // $translateProvider.useCookieStorage();
        $translateProvider.useLocalStorage();

        jwtOptionsProvider.config({
            tokenGetter: ['options', function(options) {
                if (options.url.substr(options.url.length - 5) == '.html') {
                    return null;
                }

                return localStorage.getItem('id_token');
            }]
        });

        $httpProvider.interceptors.push('jwtInterceptor');
    }
]);
