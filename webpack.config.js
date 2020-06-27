const path = require('path');
const CompressionPlugin = require('compression-webpack-plugin');

const devMode = true;

module.exports = {
  mode: devMode ? 'development' : 'production',
  plugins: [new CompressionPlugin({
    algorithm: 'gzip',
  })],
  entry: ['./src/js/app.js', './src/sass/app.scss'],
  output: {
    path: path.resolve(__dirname, 'public/dist'),
    filename: 'js/app.min.js',
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: 'css/[name].min.css',
            }
          },
          {
            loader: 'extract-loader'
          },
          {
            loader: 'css-loader?-url'
          },
          {
            loader: 'sass-loader'
          }
        ]
      }
    ]
  },
  optimization: {

  }
};