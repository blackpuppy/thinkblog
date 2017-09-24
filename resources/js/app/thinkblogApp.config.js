'use strict';

angular.module('thinkblogApp')
.factory('authHttpResponseInterceptor',['$q', '$location', function($q,$location){
    return {
        response: function(response){
            if (response.status === 401) {
                console.log("Response 401");
            }
            return response || $q.when(response);
        },
        responseError: function(rejection) {
            if (rejection.status === 401) {
                console.log("Response Error 401",rejection);
                $location.path('/login').search('returnTo', $location.path());
            }
            return $q.reject(rejection);
        }
    }
}])
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
                'header@': 'menu',
                'footer@': 'bottom'
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
            url: '/posts?filter&order&pageSize&page',
            parent: 'root',
            views: {
                'content@': 'postList'
            },
            resolve: {
                data: [
                    '$stateParams',
                    '$log',
                    'Post',
                    function ($stateParams, $log, Post) {
                        $log.info('state post-list: $stateParams = ', $stateParams);
                        return Post.query($stateParams);
                    }
                ]
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
                data: [
                    'Post', '$transition$',
                    function(Post, $transition$) {
                        return Post.get({id: $transition$.params().id});
                    }
                ]
            }
        };

        var postEditState = {
            name: 'post-edit',
            url: '/posts/{id}/edit',
            parent: 'root',
            views: {
                'content@': 'postEdit'
            },
            resolve: {
                data: [
                    'Post', '$transition$',
                    function(Post, $transition$) {
                        return Post.get({id: $transition$.params().id});
                    }
                ]
            }
        };

        var postCreateState = {
            name: 'post-create',
            url: '/post/create',
            parent: 'root',
            views: {
                'content@': 'postCreate'
            }
        };

        var profileViewState = {
            name: 'profile-view',
            url: '/profile',
            parent: 'root',
            views: {
                'content@': 'profileView'
            },
            resolve: {
                data: [
                    'Profile',
                    function(Profile) {
                        return Profile.get();
                    }
                ],
                genders: [
                    'ConfigList',
                    function(ConfigList) {
                        return ConfigList.get({ list_name: 'gender' });
                    }
                ]
            }
        };

        var profileEditState = {
            name: 'profile-edit',
            url: '/profile/edit',
            parent: 'root',
            views: {
                'content@': 'profileEdit'
            },
            resolve: {
                data: [
                    'Profile',
                    function(Profile) {
                        return Profile.get();
                    }
                ],
                genders: [
                    'ConfigList',
                    function(ConfigList) {
                        return ConfigList.get({ list_name: 'gender' });
                    }
                ]
            }
        };

        $stateProvider.state(rootState);
        $stateProvider.state(homeState);
        $stateProvider.state(loginState);
        $stateProvider.state(postListState);
        $stateProvider.state(postViewState);
        $stateProvider.state(postEditState);
        $stateProvider.state(postCreateState);
        $stateProvider.state(profileViewState);
        $stateProvider.state(profileEditState);

        $translateProvider.useUrlLoader(ThinkBlog.getUrl(ThinkBlog.URL_API_TRANSLATE))
        $translateProvider.preferredLanguage('zh-CN');
        $translateProvider.determinePreferredLanguage();
        // $translateProvider.useCookieStorage();
        $translateProvider.useLocalStorage();
        $translateProvider.useSanitizeValueStrategy('escapeParameters');

        jwtOptionsProvider.config({
            tokenGetter: ['options', function(options) {
                if (options.url.substr(options.url.length - 5) == '.html') {
                    return null;
                }

                return localStorage.getItem('id_token');
            }]
        });

        $httpProvider.interceptors.push('jwtInterceptor');

        $httpProvider.interceptors.push('authHttpResponseInterceptor');
    }
])
.run([
    '$rootScope',
    '$state',
    '$cookies',
    '$http',
    '$log',
    function run($rootScope, $state, $cookies, $http, $log) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookies.getObject('globals') || {};
        if (!!$rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Bearer ' + $rootScope.globals.token;
        }

        // $log.info('thinkblogApp.run(): $rootScope.globals = ', $rootScope.globals);
        // $log.info('thinkblogApp.run(): $state.current = ', $state.current);

        $rootScope.$on('$stateChangeStart', function (evt, toState, toParams, fromState, fromParams) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray(
                $state.current.name,
                ['home', 'login', 'signup']
            ) === -1;
            var loggedIn = !!$rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn) {
                // $log.info('thinkblogApp.run(): go to state login');

                $state.go('login');
            }
        });
    }
]);
