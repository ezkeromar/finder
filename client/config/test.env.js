'use strict'
const merge = require('webpack-merge')
const devEnv = require('./dev.env')

module.exports = merge(devEnv, {
  NODE_ENV: '"testing"',
  API_URL: '"http://finder.aramobile.com/api"',
  ASSET_URL: '"http://finder.aramobile.com/"'
})
