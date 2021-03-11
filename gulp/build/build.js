const { series, parallel } = require('gulp')
const scss = require('../task/scss')
const scssMin = require('../task/scss-min')
const vue = require('../task/vue-build')

module.exports = series(vue, parallel(scss, scssMin))
