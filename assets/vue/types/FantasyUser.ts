import { type ApiResource } from './ApiResource'
import { type FantasyTeam } from './FantasyTeam'
import { type FantasyPick } from './FantasyPick'

export interface FantasyUser extends ApiResource {
    username?: string | null
    ttflId?: number | null
    fantasyTeam?: FantasyTeam | null
    isExoticUser?: boolean | null
    isSynchronizationActive?: boolean | null
    fantasyPoints?: number | null
    fantasyRank?: number | null
    lastFantasyPick?: FantasyPick | null
}
