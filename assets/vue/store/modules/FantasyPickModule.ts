import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import FantasyPick from '../../models/api/FantasyPick'
import { fantasyPickApiHelper } from '../../helpers/api-accessor'

export type FantasyPickState = AbstractResourceState<FantasyPick>

@Module({
    namespaced: true,
    name: 'fantasyPick'
})
export default class FantasyPickModule extends AbstractResourceModule<FantasyPick> implements FantasyPickState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, fantasyPickApiHelper)
    }
}
