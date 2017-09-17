'use strict';

angular.module('thinkblogApp', [
    'ui.router',
    'ngCookies',
    'LocalStorageModule',
    'pascalprecht.translate',
    'ui.bootstrap',
    'mwl.confirm',
    'angular-jwt',

    'core.post',
    'menu',
    'bottom',
    'home',
    'postList',
    'postView'
]);
