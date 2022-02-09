import { createApp } from "vue";
import router from "./router";
import App from "./App.vue";
// import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

createApp(App)
// .use(BootstrapVue)
.use(router)
.mount("#app");
