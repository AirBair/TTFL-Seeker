import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import { router } from './router/router'

declare global {
    interface Window {
        nbaYear: number
        isNbaPlayoffs: boolean
    }
}

const pinia = createPinia()
const app = createApp(App)

app.use(vuetify)
app.use(pinia)
app.use(router)
app.mount('#app')
