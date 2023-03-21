import Vue from "vue";
import App from "./App.vue";
import vuetify from "./plugins/vuetify";
import VueIframe from "vue-iframes";
import Vue2Editor from "vue2-editor";

Vue.use(Vue2Editor);
Vue.config.productionTip = false;
Vue.use(VueIframe);

new Vue({
  vuetify,
  render: (h) => h(App),
}).$mount("#app");
