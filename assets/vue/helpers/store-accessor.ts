import { Store } from 'vuex'
import { RootState } from '../store/store'
import { getModule } from 'vuex-module-decorators'
import AppModule from '../store/modules/AppModule'
import NbaGameModule from '../store/modules/NbaGameModule'
import NbaPlayerModule from '../store/modules/NbaPlayerModule'
import NbaStatsLogModule from '../store/modules/NbaStatsLogModule'
import NbaTeamModule from '../store/modules/NbaTeamModule'

let appModule: AppModule
let nbaGameModule: NbaGameModule
let nbaPlayerModule: NbaPlayerModule
let nbaStatsLogModule: NbaStatsLogModule
let nbaTeamModule: NbaTeamModule

function initialiseStores (store: Store<RootState>): void {
    appModule = getModule(AppModule, store)
    nbaGameModule = getModule(NbaGameModule, store)
    nbaPlayerModule = getModule(NbaPlayerModule, store)
    nbaStatsLogModule = getModule(NbaStatsLogModule, store)
    nbaTeamModule = getModule(NbaTeamModule, store)
}

export {
    initialiseStores,
    appModule,
    nbaGameModule,
    nbaPlayerModule,
    nbaStatsLogModule,
    nbaTeamModule
}
