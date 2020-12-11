import Vue from 'vue'
import Vuex from 'vuex'
import AppModule, { AppState } from './modules/AppModule'
import { initialiseStores } from '../helpers/store-accessor'

Vue.use(Vuex)

export interface RootState {
    app: AppState;
}

const store = new Vuex.Store<RootState>({
    strict: true,
    modules: {
        app: AppModule
    }
})

initialiseStores(store)

export default store
