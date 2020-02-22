import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import router from './router';
import {i18n} from "./plugins/i18n";
import FlagIcon from 'vue-flag-icon';
import store from './store';

// import axios from 'axios';
// import VueAxios from 'vue-axios';

Vue.config.productionTip = false;
Vue.use(FlagIcon);
// Vue.use(VueAxios, axios);

new Vue({
  vuetify, router, i18n, store,
  render: h => h(App)
}).$mount('#app')
