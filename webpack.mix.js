const config = require('./webpack.config');
const mix = require('laravel-mix');
require('laravel-mix-eslint');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

function resolve(dir) {
  return path.join(
    __dirname,
    '/resources/js',
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

config.externals =
  {
    vue: 'Vue',
    'element-ui':'ELEMENT'
  };

config.plugins.push(new CleanWebpackPlugin());

mix.webpackConfig(config);

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

mix
  .js('resources/js/app.js', 'public/dist')
  .options({
    processCssUrls: false,
  })
  .sass('resources/js/styles/index.scss', 'public/dist/css/app.css', {
    implementation: require('node-sass'),
  })
  .setPublicPath('public/dist');

if (mix.inProduction()) {
  require('laravel-mix-versionhash');
  mix.versionHash();
} else {
  mix.eslint();
  // Development settings
  mix
    .sourceMaps()
    .webpackConfig({
      devtool: 'cheap-eval-source-map', // Fastest for development
    });
}
