import { type ApiResource } from './ApiResource'
import { type FantasyUser } from './FantasyUser'

export interface FantasyUserRanking extends ApiResource {
    fantasyUser?: FantasyUser | null
    season?: number | null
    isPlayoffs?: boolean | null
    fantasyPoints?: number | null
    fantasyRank?: number | null
    rankingAt?: string | null
    updatedAt?: string | null
}
