const path = require('path');
module.exports = {
  outputDir: path.resolve(__dirname, 'html/default/assets/js/vue'),
  pages: {
      app: {
          entry: 'frontend/src/main.js',
      }
  }
}
