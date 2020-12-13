import Vue from 'vue'
import Vuex from 'vuex'
import AppModule, { AppState } from './modules/AppModule'
import { initialiseStores } from '../helpers/store-accessor'
import FantasyPickModule, { FantasyPickState } from './modules/FantasyPickModule'
import FantasyTeamModule, { FantasyTeamState } from './modules/FantasyTeamModule'
import FantasyTeamRankingModule, { FantasyTeamRankingState } from './modules/FantasyTeamRankingModule'
import FantasyUserModule, { FantasyUserState } from './modules/FantasyUserModule'
import FantasyUserRankingModule, { FantasyUserRankingState } from './modules/FantasyUserRankingModule'
import NbaGameModule, { NbaGameState } from './modules/NbaGameModule'
import NbaPlayerModule, { NbaPlayerState } from './modules/NbaPlayerModule'
import NbaStatsLogModule, { NbaStatsLogState } from './modules/NbaStatsLogModule'
import NbaTeamModule, { NbaTeamState } from './modules/NbaTeamModule'

Vue.use(Vuex)

export interface RootState {
    app: AppState;
    fantasyPick: FantasyPickState;
    fantasyTeam: FantasyTeamState;
    fantasyTeamRanking: FantasyTeamRankingState;
    fantasyUser: FantasyUserState;
    fantasyUserRanking: FantasyUserRankingState;
    nbaGame: NbaGameState;
    nbaPlayer: NbaPlayerState;
    nbaStatsLog: NbaStatsLogState;
    nbaTeam: NbaTeamState;
}

const store = new Vuex.Store<RootState>({
    strict: true,
    modules: {
        app: AppModule,
        fantasyPick: FantasyPickModule,
        fantasyTeam: FantasyTeamModule,
        fantasyRankingTeam: FantasyTeamRankingModule,
        fantasyUser: FantasyUserModule,
        fantasyRankingUser: FantasyUserRankingModule,
        nbaGame: NbaGameModule,
        nbaPlayer: NbaPlayerModule,
        nbaStatsLog: NbaStatsLogModule,
        nbaTeam: NbaTeamModule
    }
})

initialiseStores(store)

export default store
