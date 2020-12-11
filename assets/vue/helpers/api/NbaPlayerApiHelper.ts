import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import NbaPlayer from '../../models/api/NbaPlayer'

export default class NbaPlayerApiHelper extends AbstractResourceApiHelper<NbaPlayer> {
    constructor () {
        super('/api/nba-players')
    }
}
