import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import 'vue-select/dist/vue-select.css';
import vSelect from 'vue-select'

createApp(App)
  .use(router)
  .component('v-select', vSelect)
  .mount('#app')

