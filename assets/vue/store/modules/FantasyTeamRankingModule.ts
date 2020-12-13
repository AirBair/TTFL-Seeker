import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import FantasyTeamRanking from '../../models/api/FantasyTeamRanking'
import { fantasyTeamRankingApiHelper } from '../../helpers/api-accessor'

export type FantasyTeamRankingState = AbstractResourceState<FantasyTeamRanking>

@Module({
    namespaced: true,
    name: 'fantasyTeamRanking'
})
export default class FantasyTeamRankingModule extends AbstractResourceModule<FantasyTeamRanking> implements FantasyTeamRankingState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, fantasyTeamRankingApiHelper)
    }
}
