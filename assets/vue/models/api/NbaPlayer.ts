import AbstractResource from './AbstractResource'
import NbaTeam from './NbaTeam'

export default class NbaPlayer extends AbstractResource {
    firstName?: string
    lastName?: string
    fullName?: string
    position?: string
    jersey?: number
    isInjured?: string
    nbaTeam?: NbaTeam
    averageFantasyPoints?: number
    pastYearFantasyPoints?: number
    isAllowedInExoticLeague?: boolean
    updatedAt?: string
}
