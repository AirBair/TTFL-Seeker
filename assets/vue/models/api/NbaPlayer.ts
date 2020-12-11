import AbstractResource from './AbstractResource'
import NbaTeam from './NbaTeam'

export default class NbaPlayer extends AbstractResource {
    firstName?: string
    lastName?: string
    fullName?: string
    position?: string
    jersey?: string
    isInjured?: string
    nbaTeam?: NbaTeam
    averageFantasyPoints?: string
    pastYearFantasyPoints?: string
    updatedAt?: string
}
