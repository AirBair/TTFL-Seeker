import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import FantasyTeam from '../../models/api/FantasyTeam'

export default class FantasyTeamApiHelper extends AbstractResourceApiHelper<FantasyTeam> {
    constructor () {
        super('/api/fantasy-teams')
    }
}
