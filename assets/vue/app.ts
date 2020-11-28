import Vue, { VNode } from 'vue'
import vuetify from './plugins/vuetify'
import App from './App.vue'

const vue = new Vue({
    vuetify,
    render (h): VNode {
        return h(App)
    }
})
vue.$mount('#app')
