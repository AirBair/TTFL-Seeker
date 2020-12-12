import Vue from 'vue'
import Vuex from 'vuex'
import AppModule, { AppState } from './modules/AppModule'
import { initialiseStores } from '../helpers/store-accessor'
import NbaGameModule from './modules/NbaGameModule'
import NbaPlayerModule from './modules/NbaPlayerModule'
import NbaStatsLogModule from './modules/NbaStatsLogModule'
import NbaTeamModule from './modules/NbaTeamModule'

Vue.use(Vuex)

export interface RootState {
    app: AppState;
}

const store = new Vuex.Store<RootState>({
    strict: true,
    modules: {
        app: AppModule,
        nbaGame: NbaGameModule,
        nbaPlayer: NbaPlayerModule,
        nbaStatsLog: NbaStatsLogModule,
        nbaTeam: NbaTeamModule
    }
})

initialiseStores(store)

export default store
