const config = require('./webpack.admin.config');
const mix = require('laravel-mix');
const vue_dir_path = '/Resources/vue-element-admin';

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

function resolve(dir) {
    return path.join(
        __dirname,
        vue_dir_path,
        dir
    );
}

Mix.listen('configReady', webpackConfig => {
    // Add "svg" to image loader test
    const imageLoaderConfig = webpackConfig.module.rules.find(
        rule =>
            String(rule.test) ===
            String(/(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/)
    );
    imageLoaderConfig.exclude = resolve('icons');
});

mix.webpackConfig(config);

mix
    .js(__dirname + vue_dir_path + '/main.js', 'public/js')
    .sass(__dirname + '/Resources/assets/sass/app.scss', 'public/css')
    .extract([
        'vue',
        'axios',
        'vuex',
        'vue-router',
        'vue-i18n',
        'element-ui',
        'echarts',
        'highlight.js',
        'sortablejs',
        'dropzone',
        'xlsx',
        'tui-editor',
        'codemirror',
    ])
    .options({
        processCssUrls: false,
        postCss: [
            require('autoprefixer'),
        ],
    });

if (mix.inProduction()) {
    mix.version();
} else {
    if (process.env.LARAVUE_USE_ESLINT === 'true') {
        mix.eslint();
    }
    // Development settings
    mix
        .sourceMaps()
        .webpackConfig({
            devtool: 'cheap-eval-source-map', // Fastest for development
        });
}
