import { type ApiResource } from './ApiResource'

export interface FantasyTeam extends ApiResource {
    name?: string | null
    isExoticTeam?: boolean | null
    isSynchronizationActive?: boolean | null
    fantasyPoints?: number | null
    fantasyRank?: number | null
}
