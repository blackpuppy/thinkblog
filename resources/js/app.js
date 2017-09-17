window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

// Bootstrap Confirmation
require('bootstrap-confirmation2/bootstrap-confirmation.min');

// AngularJS
require('angular/');
require('angular-animate/');
require('angular-cookies/');
require('angular-local-storage/');
require('angular-resource/');
require('angular-sanitize/');
require('angular-touch/');
require('angular-ui-router/release/angular-ui-router');
require('angular-translate/dist/angular-translate');
require('angular-translate/dist/angular-translate-loader-url/angular-translate-loader-url');
require('angular-translate/dist/angular-translate-storage-cookie/angular-translate-storage-cookie');
require('angular-translate/dist/angular-translate-storage-local/angular-translate-storage-local');
require('angular-ui-bootstrap/');
require('angular-bootstrap-confirm/dist/angular-bootstrap-confirm');
require('angular-jwt/');

// Application JavaScript.
require('./thinkblog');
require('./app/thinkblogApp.module');
require('./app/thinkblogApp.config');
require('./app/core/');
require('./app/menu/');
require('./app/bottom/');
require('./app/home/');
require('./app/post-list/');
require('./app/post-view/');
