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
    // .setResourceRoot('webroot/Public')
    .setPublicPath(path.normalize('webroot/Public'))
    .setResourceRoot(path.normalize('webroot/Public'));
    // .disableNotifications();

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync('localhost:8084'); // Hot reloading
