import AbstractApiHelper from './AbstractApiHelper'
import { type FantasyTeam } from '../types/FantasyTeam'

class FantasyTeamApiHelper extends AbstractApiHelper<FantasyTeam> {
    constructor() {
        super('/api/fantasy-teams')
    }
}
export const fantasyTeamApiHelper = new FantasyTeamApiHelper()
