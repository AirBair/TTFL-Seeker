import { type ApiResource } from './ApiResource'
import { type NbaTeam } from './NbaTeam'

export interface NbaPlayer extends ApiResource {
    firstName?: string | null
    lastName?: string | null
    fullName?: string | null
    position?: string | null
    jersey?: number | null
    isInjured?: boolean | null
    nbaTeam?: NbaTeam | null
    averageFantasyPoints?: number | null
    pastYearFantasyPoints?: number | null
    isAllowedInExoticLeague?: boolean | null
    updatedAt?: string | null
}
