import Vue, { VNode } from 'vue'
import App from './App.vue'
import router from './plugins/router'
import store from './store/store'
import vuetify from './plugins/vuetify'

const vue = new Vue({
    router,
    store,
    vuetify,
    render (h): VNode {
        return h(App)
    }
})
vue.$mount('#app')
