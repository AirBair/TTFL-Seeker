import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import NbaTeam from '../../models/api/NbaTeam'

export default class NbaTeamApiHelper extends AbstractResourceApiHelper<NbaTeam> {
    constructor () {
        super('/api/nba-teams')
    }
}
