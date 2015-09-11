var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require('extract-text-webpack-plugin')
var AssetsPlugin = require('assets-webpack-plugin')

var defaultEnv = 'varaa'
var env = process.env.NODE_ENV || defaultEnv
var rootPath = path.join(__dirname, 'resources/' + env)

var config = {
  context: rootPath,
  entry: {
    app: 'app',
    vendors: []
  },
  resolve: {
    alias: {},
    root: [rootPath],
    extensions: ['', '.es6', '.js']
  },
  output: {
    path: path.join(__dirname, 'public/test'),
    filename: '[name].js'
  },
  module: {
    noParse: [],
    loaders: [
      { test: /\.css$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader') },
      { test: /\.(jpg|gif|png)/, loader: 'url-loader' },
      { test: /\.(svg|eot|ttf|woff|woff2)/, loader: 'file-loader' },
      { test: /\.less/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader!less-loader') },
      { test: /\.es6/, loader: 'babel-loader' }
    ]
  },
  plugins: [
    new webpack.optimize.CommonsChunkPlugin('vendors', 'vendors.js'),
    new ExtractTextPlugin('[name].css')
  ]
}

if (env !== defaultEnv) {
  config.output.filename = '[name]-[hash].js'
  // Only compress vendor files in production
  config.plugins = [
    new webpack.optimize.UglifyJsPlugin({warnings: false}),
    new webpack.optimize.CommonsChunkPlugin('vendors', 'vendors-[hash].js'),
    new ExtractTextPlugin('[name]-[hash].css'),
    new AssetsPlugin({filename: 'rev.json'})
  ]
}

module.exports = config
