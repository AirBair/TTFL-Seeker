import AbstractResource from './AbstractResource'
import FantasyTeam from './FantasyTeam'

export default class FantasyTeamRanking extends AbstractResource {
    fantasyTeam?: FantasyTeam
    season?: number
    isPlayoffs?: boolean
    fantasyPoints?: number
    fantasyRank?: number
    rankingAt?: string
    updatedAt?: string
}
