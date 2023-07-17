//Assets
import './assets/main.less'
import 'ionicons-npm/css/ionicons.css'
import 'font-awesome/css/font-awesome.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'

const app = createApp(App)

app.use(createPinia())

app.mount('#app')
