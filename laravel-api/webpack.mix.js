const mix = require('laravel-mix');

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

// 引入 Admin 模块下的 webpack 配置文件
require(`${__dirname}/app/Modules/Admin/webpack.admin.js`);


// mix.js('resources/js/app.js', 'public/' + vue_path)
//     .postCss('resources/css/app.css', 'public/' + vue_path, [
//         //
//     ]);
