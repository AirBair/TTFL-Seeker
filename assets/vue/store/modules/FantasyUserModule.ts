import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import FantasyUser from '../../models/api/FantasyUser'
import { fantasyUserApiHelper } from '../../helpers/api-accessor'

export type FantasyUserState = AbstractResourceState<FantasyUser>

@Module({
    namespaced: true,
    name: 'fantasyUser'
})
export default class FantasyUserModule extends AbstractResourceModule<FantasyUser> implements FantasyUserState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, fantasyUserApiHelper)
    }
}
