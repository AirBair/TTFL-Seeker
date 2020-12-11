import { Store } from 'vuex'
import { RootState } from '../store/store'
import { getModule } from 'vuex-module-decorators'
import AppModule from '../store/modules/AppModule'

let appModule: AppModule

function initialiseStores (store: Store<RootState>): void {
    appModule = getModule(AppModule, store)
}

export {
    initialiseStores,
    appModule
}
