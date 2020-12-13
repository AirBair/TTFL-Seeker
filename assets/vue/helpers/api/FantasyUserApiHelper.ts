import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import FantasyUser from '../../models/api/FantasyUser'

export default class FantasyUserApiHelper extends AbstractResourceApiHelper<FantasyUser> {
    constructor () {
        super('/api/fantasy-users')
    }
}
