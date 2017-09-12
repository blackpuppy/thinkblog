window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

// Bootstrap Confirmation
require('bootstrap-confirmation2/bootstrap-confirmation.min');

// AngularJS
require('angular/angular');
require('angular-route/angular-route');
require('angular-resource/angular-resource');

// Application JavaScript.
require('./thinkblog');
require('./app/thinkblogApp.module');
require('./app/thinkblogApp.config');
require('./app/core/');
require('./app/post-list/');
require('./app/post-view/');
