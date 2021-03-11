const { task } = require('gulp')
const { exec } = require('child_process')

function build() {
  exec('yarn vue-watch')
}

module.exports = (done) => {
  task( 'vue', build() )
  done()
}
