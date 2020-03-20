const mix = require('laravel-mix');
const webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.options({
  terser: {
    terserOptions: {
      compress: {
        drop_console: true,
      },
    },
  },
})
  .setPublicPath('public')
  // .js('resources/js/app.js', 'public/js')
  // .sass('resources/sass/app.scss', 'public/css')
  .version()
  .copy('resources/js/vue.js', 'public/js')
  .copy('resources/js/element.js', 'public/js')
  .copy('resources/js/axios.min.js', 'public/js')
  .copy('resources/sass/element.css', 'public/css')
  .copy('resources/fonts', 'public/fonts')
  .copy('resources/img', 'public/img')
  .webpackConfig({
    resolve: {
      symlinks: false,
      alias: {
        '@': path.resolve(__dirname, 'resources/js/'),
      },
    }
  });
