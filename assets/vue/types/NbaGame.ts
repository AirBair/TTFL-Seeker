import { type ApiResource } from './ApiResource'
import { type NbaTeam } from './NbaTeam'

export interface NbaGame extends ApiResource {
    season?: number | null
    isPlayoffs?: boolean | null
    gameDay?: string | null
    scheduledAt?: string | null
    localNbaTeam?: NbaTeam | null
    visitorNbaTeam?: NbaTeam | null
    localScore?: number | null
    visitorScore?: number | null
    updatedAt?: string | null
}
