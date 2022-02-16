import { createApp } from "vue";
import router from "./router";
import App from "./App.vue";
import Vuex from 'Vuex';

// import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

createApp(App)
// .use(BootstrapVue)
.use(router)
.use(Vuex)
.mount("#app");
