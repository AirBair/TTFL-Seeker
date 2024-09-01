import AbstractApiHelper from './AbstractApiHelper'
import { type FantasyTeamRanking } from '../types/FantasyTeamRanking'

class FantasyTeamRankingApiHelper extends AbstractApiHelper<FantasyTeamRanking> {
    constructor() {
        super('/api/fantasy-team-rankings')
    }
}

export const fantasyTeamRankingApiHelper = new FantasyTeamRankingApiHelper()
