import { type RouteLocationNormalizedLoaded } from 'vue-router'
import { createRouter, createWebHistory } from 'vue-router'
import NbaPlayersList from '../pages/NbaPlayersList.vue'
import NbaPlayerProfile from '../pages/NbaPlayerProfile.vue'
import FantasyUsersList from '../pages/FantasyUsersList.vue'
import FantasyUserProfile from '../pages/FantasyUserProfile.vue'
import FantasyTeamsList from '../pages/FantasyTeamsList.vue'
import FantasyTeamProfile from '../pages/FantasyTeamProfile.vue'
import PicksOfTheDay from '../pages/PicksOfTheDay.vue'

declare module 'vue-router' {
    interface RouteMeta {
        navbarLabel?: string
    }
}

export const router = createRouter({
    history: createWebHistory(),
    routes: [{
        path: '/',
        redirect: { name: 'picks_of_the_day' }
    }, {
        path: '/nba-players/list',
        name: 'nba_players_list',
        component: NbaPlayersList,
        meta: {
            navbarLabel: 'NBA Players'
        }
    }, {
        path: '/nba-players/:id',
        name: 'nba_player_profile',
        component: NbaPlayerProfile,
        props: (route: RouteLocationNormalizedLoaded) => ({ id: Number(route.params.id) }),
        meta: {
            navbarLabel: 'Nba Player Profile'
        }
    }, {
        path: '/picks-of-the-day',
        name: 'picks_of_the_day',
        component: PicksOfTheDay,
        meta: {
            navbarLabel: 'Picks of the Day'
        }
    }, {
        path: '/fantasy-users/list',
        name: 'fantasy_users_list',
        component: FantasyUsersList,
        meta: {
            navbarLabel: 'Fantasy Users'
        }
    }, {
        path: '/fantasy-users/:id',
        name: 'fantasy_user_profile',
        component: FantasyUserProfile,
        props: (route: RouteLocationNormalizedLoaded) => ({ id: Number(route.params.id) }),
        meta: {
            navbarLabel: 'Fantasy User Profile'
        }
    }, {
        path: '/fantasy-teams/list',
        name: 'fantasy_teams_list',
        component: FantasyTeamsList,
        meta: {
            navbarLabel: 'Fantasy Teams'
        }
    }, {
        path: '/fantasy-teams/:id',
        name: 'fantasy_team_profile',
        component: FantasyTeamProfile,
        props: (route: RouteLocationNormalizedLoaded) => ({ id: Number(route.params.id) }),
        meta: {
            navbarLabel: 'Fantasy Team Profile'
        }
    }]
})
