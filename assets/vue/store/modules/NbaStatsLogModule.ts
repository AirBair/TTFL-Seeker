import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import NbaStatsLog from '../../models/api/NbaStatsLog'
import { nbaStatsLogApiHelper } from '../../helpers/api-accessor'

export type NbaStatsLogState = AbstractResourceState<NbaStatsLog>

@Module({
    namespaced: true,
    name: 'nbaStatsLog'
})
export default class NbaStatsLogModule extends AbstractResourceModule<NbaStatsLog> implements NbaStatsLogState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, nbaStatsLogApiHelper)
    }
}
