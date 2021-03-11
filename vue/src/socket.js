import Vue from 'vue'
import VueSSE from 'vue-sse';
Vue.use(VueSSE, {
  format: 'json', // parse messages as JSON
  polyfill: true, // support older browsers
  url: '/hub', // default sse server url
  withCredentials: true, // send cookies
});
