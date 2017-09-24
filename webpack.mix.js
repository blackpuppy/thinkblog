const mix = require('laravel-mix');
const path = require('path');

// mix.webpackConfig({
//     output: {
//         path: path.resolve(__dirname, 'webroot/Public'),
//         pathinfo: process.env.NODE_ENV !== 'production'
//         // publicPath: path.normalize('webroot/Public')
//     },
//     module: {
//         rules: [
//             {
//                 test: /\.(woff2?|ttf|eot|svg|otf)$/,
//                 loader: 'file-loader',
//                 options: {
//                     name: '[path][name].[ext]?[hash]',
//                     outputPath: '/fonts/',
//                     context: 'public',
//                     emitFile: true
//                 }
//             }
//         ]
//     }
// });

// console.log('normalized webroot/Public = ', path.normalize('webroot/Public'));
// console.log('resolved webroot/Public = ', path.resolve(__dirname, 'webroot/Public'));

mix.sass('resources/sass/app.scss', 'css')
    .js('resources/js/app.js', 'js')
    // .setPublicPath('webroot/Public')
    .setPublicPath(path.normalize('webroot/Public'))
    .setResourceRoot('/Public/');
    // .setResourceRoot(path.normalize('/Public/'));
    // .disableNotifications();

if (!mix.inProduction()) {
	mix.sourceMaps();
}

// cannot keep folder structure, have to copy one folder by one folder
// mix.copy('./resources/js/app/root/*.html', './webroot/Public/template/root');
mix.copy('./resources/js/app/menu/*.html', './webroot/Public/template/menu');
mix.copy('./resources/js/app/bottom/*.html', './webroot/Public/template/bottom');
mix.copy('./resources/js/app/home/*.html', './webroot/Public/template/home');
mix.copy('./resources/js/app/signup/*.html', './webroot/Public/template/signup');
mix.copy('./resources/js/app/login/*.html', './webroot/Public/template/login');
mix.copy('./resources/js/app/post-list/*.html', './webroot/Public/template/post-list');
mix.copy('./resources/js/app/post-view/*.html', './webroot/Public/template/post-view');
mix.copy('./resources/js/app/post-edit/*.html', './webroot/Public/template/post-edit');
mix.copy('./resources/js/app/post-create/*.html', './webroot/Public/template/post-create');
mix.copy('./resources/js/app/profile-view/*.html', './webroot/Public/template/profile-view');
mix.copy('./resources/js/app/profile-edit/*.html', './webroot/Public/template/profile-edit');

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync('localhost:8084'); // Hot reloading
