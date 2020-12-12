import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import NbaGame from '../../models/api/NbaGame'
import { nbaGameApiHelper } from '../../helpers/api-accessor'

export type NbaGameState = AbstractResourceState<NbaGame>

@Module({
    namespaced: true,
    name: 'nbaGame'
})
export default class NbaGameModule extends AbstractResourceModule<NbaGame> implements NbaGameState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, nbaGameApiHelper)
    }
}
