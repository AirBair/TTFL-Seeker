import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import NbaPlayer from '../../models/api/NbaPlayer'
import { nbaPlayerApiHelper } from '../../helpers/api-accessor'

export type NbaPlayerState = AbstractResourceState<NbaPlayer>

@Module({
    namespaced: true,
    name: 'nbaPlayer'
})
export default class NbaPlayerModule extends AbstractResourceModule<NbaPlayer> implements NbaPlayerState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, nbaPlayerApiHelper)
    }
}
