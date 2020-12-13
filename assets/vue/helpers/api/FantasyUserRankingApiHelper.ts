import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import FantasyUserRanking from '../../models/api/FantasyUserRanking'

export default class FantasyUserRankingApiHelper extends AbstractResourceApiHelper<FantasyUserRanking> {
    constructor () {
        super('/api/fantasy-user-rankings')
    }
}
