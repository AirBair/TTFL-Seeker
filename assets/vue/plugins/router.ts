import Vue from 'vue'
import VueRouter, { Route } from 'vue-router'
import NbaPlayersList from '../components/NbaPlayersList.vue'
import NbaPlayerProfile from '../components/NbaPlayerProfile.vue'

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
    }, {
        path: '/nba-players/:nbaPlayerId',
        name: 'nba_player_profile',
        component: NbaPlayerProfile,
        props: true,
        meta: {
            navbarLabel: 'Nba Player Profile'
        }
    }]
})

export default router
