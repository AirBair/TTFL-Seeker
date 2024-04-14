import AbstractApiHelper from './AbstractApiHelper'
import { type NbaTeam } from '../types/NbaTeam'

class NbaTeamApiHelper extends AbstractApiHelper<NbaTeam> {
    constructor () {
        super('/api/nba-teams')
    }
}

export const nbaTeamApiHelper = new NbaTeamApiHelper()
