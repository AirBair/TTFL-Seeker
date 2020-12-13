import { Module } from 'vuex-module-decorators'
import { Module as Mod } from 'vuex'
import AbstractResourceModule, { AbstractResourceState } from './AbstractResourceModule'
import FantasyUserRanking from '../../models/api/FantasyUserRanking'
import { fantasyUserRankingApiHelper } from '../../helpers/api-accessor'

export type FantasyUserRankingState = AbstractResourceState<FantasyUserRanking>

@Module({
    namespaced: true,
    name: 'fantasyUserRanking'
})
export default class FantasyUserRankingModule extends AbstractResourceModule<FantasyUserRanking> implements FantasyUserRankingState {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Mod<ThisType<any>, any>) {
        super(module, fantasyUserRankingApiHelper)
    }
}
