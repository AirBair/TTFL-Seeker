import { Store } from 'vuex'
import { RootState } from '../store/store'
import { getModule } from 'vuex-module-decorators'
import AppModule from '../store/modules/AppModule'
import NbaPlayerModule from '../store/modules/NbaPlayerModule'

let appModule: AppModule
let nbaPlayerModule: NbaPlayerModule

function initialiseStores (store: Store<RootState>): void {
    appModule = getModule(AppModule, store)
    nbaPlayerModule = getModule(NbaPlayerModule, store)
}

export {
    initialiseStores,
    appModule,
    nbaPlayerModule
}
