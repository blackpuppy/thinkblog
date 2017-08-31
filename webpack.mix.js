const mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig({
    output: {
        path: path.resolve(__dirname, 'webroot/Public'),
        pathinfo: process.env.NODE_ENV !== 'production'
        // publicPath: '/Public/'
    },
    module: {
        rules: [
            {
                test: /\.(woff2?|ttf|eot|svg|otf)$/,
                loader: 'file-loader',
                options: {
                    name: '[path][name].[ext]?[hash]',
                    outputPath: '/fonts/',
                    context: 'public',
                    emitFile: true
                }
            }
        ]
    }
});

mix.sass('resources/sass/app.scss', 'css')
    .js('resources/js/app.js', 'js')
    .setPublicPath('webroot/Public')
    .setResourceRoot('webroot/Public');

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync('localhost:8084'); // Hot reloading
