import AbstractApiHelper from './AbstractApiHelper'
import { type FantasyUserRanking } from '../types/FantasyUserRanking'

class FantasyUserRankingApiHelper extends AbstractApiHelper<FantasyUserRanking> {
    constructor() {
        super('/api/fantasy-user-rankings')
    }
}

export const fantasyUserRankingApiHelper = new FantasyUserRankingApiHelper()
