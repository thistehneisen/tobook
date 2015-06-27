var args              = require('yargs');
var webpack           = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

var bowerDir = __dirname + '/bower_components';
var nodeDir  = __dirname + '/node_modules';

var config = {
  addVendor: function(name, path) {
    this.resolve.alias[name] = path;
    this.entry.vendors.push(name);
  },
  entry: {
    vendors: []
  },
  resolve: {
    alias: {}
  },
  output: {
    path: __dirname + '/public/assets',
    filename: '[name].js'
  },
  module: {
    noParse: [],
    loaders: [
      { test: /\.css$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader') },
      { test: /\.(png|woff|woff2)/, loader: 'url-loader' },
      { test: /\.(svg|eot|ttf)/, loader: 'file-loader' },
      // Define dependencies
      { test: /bootstrap\.js/, loader: 'imports?jQuery=jquery' }
    ]
  },
  plugins: [
    new webpack.optimize.CommonsChunkPlugin('vendors', 'vendors.js'),
    new ExtractTextPlugin('vendors.css')
  ]
};

config.addVendor('jquery', bowerDir + '/jquery/dist/jquery.js');
config.addVendor('bootstrap', bowerDir + '/bootstrap/dist/js/bootstrap.js');
config.addVendor('bootstrap_css', bowerDir + '/bootstrap/dist/css/bootstrap.css');

if (args.argv.production) {
    // Only compress vendor files in production
    config.plugins.push(new webpack.optimize.UglifyJsPlugin({
        warnings: false
    }));
}

module.exports = config;
