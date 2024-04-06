import { type ApiResource } from './ApiResource'
import { type NbaPlayer } from './NbaPlayer'
import { type FantasyUser } from './FantasyUser'

export interface FantasyPick extends ApiResource {
    season?: number | null
    isPlayoffs?: boolean | null
    pickedAt?: string | null
    fantasyUser?: FantasyUser | null
    nbaPlayer?: NbaPlayer | null
    isNoPick?: boolean | null
    fantasyPoints?: number | null
}
