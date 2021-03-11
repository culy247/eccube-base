const { series, parallel, watch } = require('gulp')
const config = require('../config')
const server = require('../task/server')
const scss = require('../task/scss')
const scssMin = require('../task/scss-min')
const vue = require('../task/vue-dev')

module.exports = series( parallel(server, vue), parallel(scss, scssMin), () => {
  watch([config.paths.source.template + config.paths.assets.scss], parallel(scss, scssMin))
})

