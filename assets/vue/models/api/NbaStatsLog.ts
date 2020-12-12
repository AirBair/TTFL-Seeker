import AbstractResource from './AbstractResource'
import NbaGame from './NbaGame'
import NbaPlayer from './NbaPlayer'
import NbaTeam from './NbaTeam'

export default class NbaStatsLog extends AbstractResource {
    nbaGame?: NbaGame
    nbaPlayer?: NbaPlayer
    nbaTeam?: NbaTeam
    points?: number
    assists?: number
    rebounds?: number
    steals?: number
    blocks?: number
    turnovers?: number
    fieldGoals?: number
    fieldGoalsAttempts?: number
    threePointsFieldGoals?: number
    threePointsFieldGoalsAttempts?: number
    freeThrows?: number
    freeThrowsAttempts?: number
    minutesPlayed?: number
    hasWon?: boolean
    fantasyPoints?: number
    isBestPick?: boolean
    updatedAt?: string
}
