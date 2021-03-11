
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import env from './config'

Vue.use(VueI18n);

console.log(env, '>>>>>>')
const i18n = new VueI18n({
  locale: env.current_locale,
  fallbackLocale: env.default_locale, // set fallback locale
  messages: env.messages, // set locale messages
});

export default i18n
