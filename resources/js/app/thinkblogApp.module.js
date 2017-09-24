'use strict';

angular.module('thinkblogApp', [
    'ui.router',
    'ngCookies',
    'LocalStorageModule',
    'pascalprecht.translate',
    'ui.bootstrap',
    'mwl.confirm',
    'angular-jwt',

    'core',
    'menu',
    'bottom',
    'home',
    'login',
    'postList',
    'postView',
    'postEdit',
    'postCreate',
    'profileView',
    'profileEdit'
]);
