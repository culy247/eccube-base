const browserSync = require('browser-sync')
const config = require('../config')

module.exports = (done) => {
  browserSync({
    proxy: config.server,
    watch: true,
    files: [config.paths.output.template + '/**/*']
  })
  done()
}
