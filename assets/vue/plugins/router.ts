import Vue from 'vue'
import VueRouter, { Route } from 'vue-router'
import NbaPlayersList from '../components/NbaPlayersList.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [{
        path: '*', redirect: '/nba-players/list'
    }, {
        path: '/nba-players/list',
        name: 'nba_players_list',
        component: NbaPlayersList,
        props: (route: Route): unknown => ({ ...route.params, ...route.query }),
        meta: {
            navbarLabel: 'NBA Players'
        }
    }]
})

export default router
