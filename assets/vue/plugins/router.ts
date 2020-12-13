import Vue from 'vue'
import VueRouter, { Route } from 'vue-router'
import NbaPlayersList from '../components/NbaPlayersList.vue'
import NbaPlayerProfile from '../components/NbaPlayerProfile.vue'
import FantasyUsersList from '../components/FantasyUsersList.vue'
import FantasyUserProfile from '../components/FantasyUserProfile.vue'
import FantasyTeamsList from '../components/FantasyTeamsList.vue'
import FantasyTeamProfile from '../components/FantasyTeamProfile.vue'

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
    }, {
        path: '/fantasy-users/list',
        name: 'fantasy_users_list',
        component: FantasyUsersList,
        props: (route: Route): unknown => ({ ...route.params, ...route.query }),
        meta: {
            navbarLabel: 'Fantasy Users'
        }
    }, {
        path: '/fantasy-users/:fantasyUserId',
        name: 'fantasy_user_profile',
        component: FantasyUserProfile,
        props: true,
        meta: {
            navbarLabel: 'Fantasy User Profile'
        }
    }, {
        path: '/fantasy-teams/list',
        name: 'fantasy_teams_list',
        component: FantasyTeamsList,
        props: (route: Route): unknown => ({ ...route.params, ...route.query }),
        meta: {
            navbarLabel: 'Fantasy Teams'
        }
    }, {
        path: '/fantasy-teams/:fantasyTeamId',
        name: 'fantasy_team_profile',
        component: FantasyTeamProfile,
        props: true,
        meta: {
            navbarLabel: 'Fantasy Team Profile'
        }
    }]
})

export default router
