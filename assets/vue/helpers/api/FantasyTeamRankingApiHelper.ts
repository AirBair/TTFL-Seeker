import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import FantasyTeamRanking from '../../models/api/FantasyTeamRanking'

export default class FantasyTeamRankingApiHelper extends AbstractResourceApiHelper<FantasyTeamRanking> {
    constructor () {
        super('/api/fantasy-team-rankings')
    }
}
