'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  API_URL: '"http://finderbackend.test/api/"',
  UPLOAD_URL: '"http://finderbackend.test/"',
  ASSET_URL: '"http://finderbackend.test/"'
})
