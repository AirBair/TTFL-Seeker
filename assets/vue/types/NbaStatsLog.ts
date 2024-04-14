import { type ApiResource } from './ApiResource'
import { type NbaGame } from './NbaGame'
import { type NbaPlayer } from './NbaPlayer'
import { type NbaTeam } from './NbaTeam'

export interface NbaStatsLog extends ApiResource {
    nbaGame?: NbaGame | null
    nbaPlayer?: NbaPlayer | null
    nbaTeam?: NbaTeam | null
    points?: number | null
    assists?: number | null
    rebounds?: number | null
    steals?: number | null
    blocks?: number | null
    turnovers?: number | null
    fieldGoals?: number | null
    fieldGoalsAttempts?: number | null
    threePointsFieldGoals?: number | null
    threePointsFieldGoalsAttempts?: number | null
    freeThrows?: number | null
    freeThrowsAttempts?: number | null
    minutesPlayed?: number | null
    hasWon?: boolean | null
    fantasyPoints?: number | null
    isBestPick?: boolean | null
    updatedAt?: string | null
}
