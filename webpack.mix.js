const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'webroot/Public/js')
   .sass('resources/sass/app.scss', 'webroot/Public/css');

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync('localhost:8084'); // Hot reloading
