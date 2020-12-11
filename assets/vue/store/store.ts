import Vue from 'vue'
import Vuex from 'vuex'
import AppModule, { AppState } from './modules/AppModule'
import { initialiseStores } from '../helpers/store-accessor'
import NbaPlayerModule from './modules/NbaPlayerModule'

Vue.use(Vuex)

export interface RootState {
    app: AppState;
}

const store = new Vuex.Store<RootState>({
    strict: true,
    modules: {
        app: AppModule,
        nbaPlayer: NbaPlayerModule
    }
})

initialiseStores(store)

export default store
