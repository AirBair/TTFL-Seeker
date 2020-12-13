import AbstractResource from './AbstractResource'
import FantasyUser from './FantasyUser'

export default class FantasyUserRanking extends AbstractResource {
    fantasyUser?: FantasyUser
    season?: number
    isPlayoffs?: boolean
    fantasyPoints?: number
    fantasyRank?: number
    rankingAt?: string
    updatedAt?: string
}
