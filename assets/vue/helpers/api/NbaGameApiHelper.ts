import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import NbaGame from '../../models/api/NbaGame'

export default class NbaGameApiHelper extends AbstractResourceApiHelper<NbaGame> {
    constructor () {
        super('/api/nba-games')
    }
}
