import { Store } from 'vuex'
import { RootState } from '../store/store'
import { getModule } from 'vuex-module-decorators'
import AppModule from '../store/modules/AppModule'
import FantasyPickModule from '../store/modules/FantasyPickModule'
import FantasyTeamModule from '../store/modules/FantasyTeamModule'
import FantasyTeamRankingModule from '../store/modules/FantasyTeamRankingModule'
import FantasyUserModule from '../store/modules/FantasyUserModule'
import FantasyUserRankingModule from '../store/modules/FantasyUserRankingModule'
import NbaGameModule from '../store/modules/NbaGameModule'
import NbaPlayerModule from '../store/modules/NbaPlayerModule'
import NbaStatsLogModule from '../store/modules/NbaStatsLogModule'
import NbaTeamModule from '../store/modules/NbaTeamModule'

let appModule: AppModule
let fantasyPickModule: FantasyPickModule
let fantasyTeamModule: FantasyTeamModule
let fantasyTeamRankingModule: FantasyTeamRankingModule
let fantasyUserModule: FantasyUserModule
let fantasyUserRankingModule: FantasyUserRankingModule
let nbaGameModule: NbaGameModule
let nbaPlayerModule: NbaPlayerModule
let nbaStatsLogModule: NbaStatsLogModule
let nbaTeamModule: NbaTeamModule

function initialiseStores (store: Store<RootState>): void {
    appModule = getModule(AppModule, store)
    fantasyPickModule = getModule(FantasyPickModule, store)
    fantasyTeamModule = getModule(FantasyTeamModule, store)
    fantasyTeamRankingModule = getModule(FantasyTeamRankingModule, store)
    fantasyUserModule = getModule(FantasyUserModule, store)
    fantasyUserRankingModule = getModule(FantasyUserRankingModule, store)
    nbaGameModule = getModule(NbaGameModule, store)
    nbaPlayerModule = getModule(NbaPlayerModule, store)
    nbaStatsLogModule = getModule(NbaStatsLogModule, store)
    nbaTeamModule = getModule(NbaTeamModule, store)
}

export {
    initialiseStores,
    appModule,
    fantasyPickModule,
    fantasyTeamModule,
    fantasyTeamRankingModule,
    fantasyUserModule,
    fantasyUserRankingModule,
    nbaGameModule,
    nbaPlayerModule,
    nbaStatsLogModule,
    nbaTeamModule
}
