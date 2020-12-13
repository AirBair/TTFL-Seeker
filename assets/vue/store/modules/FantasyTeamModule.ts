import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import FantasyTeam from '../../models/api/FantasyTeam'
import { fantasyTeamApiHelper } from '../../helpers/api-accessor'

export type FantasyTeamState = AbstractResourceState<FantasyTeam>

@Module({
    namespaced: true,
    name: 'fantasyTeam'
})
export default class FantasyTeamModule extends AbstractResourceModule<FantasyTeam> implements FantasyTeamState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, fantasyTeamApiHelper)
    }
}
