const path = require('path');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

function resolve(dir) {
  return path.join(
    __dirname,
    '/resources/js',
    dir
  );
}

const rawArgv = process.argv.slice(2);
const report = rawArgv.includes('--report');
const plugins = [];
if (report) {
  plugins.push(new BundleAnalyzerPlugin({
    openAnalyzer: true,
  }));
}
module.exports = {
  output: {
    publicPath: '/dist/', // dynamic loading base url
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      vue$: 'vue/dist/vue.esm.js',
      '@': path.join(__dirname, '/resources/js'),
      'Components': path.join(__dirname, '/resources/js/components'),
      'Directive': path.join(__dirname, '/resources/js/directive'),
      'Filters': path.join(__dirname, '/resources/js/filters'),
      'Vendor': path.join(__dirname, '/resources/js/vendor'),
      'Utils': path.join(__dirname, '/resources/js/utils'),
      'Consts': path.join(__dirname, '/resources/js/consts'),
    },
  },
  module: {
    rules: [
      {
        test: /\.svg$/,
        loader: 'svg-sprite-loader',
        include: [resolve('icons')],
        options: {
          symbolId: 'icon-[name]',
        },
      },
    ],
  },
  plugins: plugins,
};
