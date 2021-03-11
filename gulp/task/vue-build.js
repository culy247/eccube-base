const { task } = require('gulp')
const { exec } = require('process')

module.exports = (done) => {
  task( 'vue', () => exec('yarn vue-build') )
  done()
}
