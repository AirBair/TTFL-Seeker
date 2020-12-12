import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import NbaTeam from '../../models/api/NbaTeam'
import { nbaTeamApiHelper } from '../../helpers/api-accessor'

export type NbaTeamState = AbstractResourceState<NbaTeam>

@Module({
    namespaced: true,
    name: 'nbaTeam'
})
export default class NbaTeamModule extends AbstractResourceModule<NbaTeam> implements NbaTeamState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, nbaTeamApiHelper)
    }
}
