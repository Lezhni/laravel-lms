let mix = require('laravel-mix');
let path = require('path')

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.mp3$/,
                loader: 'url-loader'
            }
        ]
    }
});

mix.browserSync({
    proxy: 'http://lms.loc',
});

mix.alias({
    '@': path.join(__dirname, 'resources/lms-client')
});

mix.copyDirectory('resources/lms-client/assets/images', 'public/assets/images')
mix.copyDirectory('resources/lms-client/assets/fonts', 'public/assets/fonts')
mix.copyDirectory('resources/lms-client/static', 'public/static')
mix.js('resources/lms-client/main.js', 'public/js').setPublicPath('public').vue().options({
    processCssUrls: false
})

mix.styles('resources/nova/style.scss', 'public/css/style.css').setPublicPath('public')

if (mix.inProduction()) {
    mix.version();
}



