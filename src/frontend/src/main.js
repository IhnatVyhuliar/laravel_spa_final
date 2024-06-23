import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { VueReCaptcha } from "vue-recaptcha-v3";

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(VueReCaptcha, {
    siteKey: '6LcRuf8pAAAAAGVPHtJ98ealBmvxiQTQzx2XCStP',
    loaderOptions: {
    useRecaptchaNet: true,
  },
})
app.use(router)

app.mount('#app')
