import { type ApiResource } from './ApiResource'
import { type FantasyTeam } from './FantasyTeam'

export interface FantasyTeamRanking extends ApiResource {
    fantasyTeam?: FantasyTeam | null
    season?: number | null
    isPlayoffs?: boolean | null
    fantasyPoints?: number | null
    fantasyRank?: number | null
    rankingAt?: string | null
    updatedAt?: string | null
}
