import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Cookies from 'js-cookie';
import elementEnLocale from 'element-ui/lib/locale/lang/en'; // element-ui lang
import elementZhLocale from 'element-ui/lib/locale/lang/zh-CN';// element-ui lang
import enLocale from './en';
import zhLocale from './zh';

Vue.use(VueI18n);

const messages = {
  en: {
    ...enLocale,
    ...elementEnLocale,
  },
  zh: {
    ...zhLocale,
    ...elementZhLocale,
  },
};

export function getLanguage() {
  const chooseLanguage = Cookies.get('language');
  if (chooseLanguage) {
    return chooseLanguage;
  }

  // if has not choose language
  const language = (navigator.language || navigator.browserLanguage).toLowerCase();
  const locales = Object.keys(messages);
  var lang = 'en';
  for (const locale of locales) {
    if (language.indexOf(locale) > -1) {
      lang = locale;
    }
  }

  Cookies.set('language', lang);
  return lang;
}
const i18n = new VueI18n({
  // set locale
  // options: en | ru | vi | zh
  locale: getLanguage(),
  // set locale messages
  messages,
  silentTranslationWarn: true,
});

export default i18n;
