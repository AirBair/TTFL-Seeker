import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import NbaStatsLog from '../../models/api/NbaStatsLog'

export default class NbaStatsLogApiHelper extends AbstractResourceApiHelper<NbaStatsLog> {
    constructor () {
        super('/api/nba-stats-logs')
    }
}
