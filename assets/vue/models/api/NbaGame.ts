import AbstractResource from './AbstractResource'
import NbaTeam from './NbaTeam'

export default class NbaGame extends AbstractResource {
    season?: number
    isPlayoffs?: boolean
    gameDay?: string
    scheduledAt?: string
    localNbaTeam?: NbaTeam
    visitorNbaTeam?: NbaTeam
    localScore?: number
    visitorScore?: number
    updatedAt?: string
}
