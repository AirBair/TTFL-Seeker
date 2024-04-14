import AbstractApiHelper from './AbstractApiHelper'
import { type NbaStatsLog } from '../types/NbaStatsLog'

class NbaStatsLogApiHelper extends AbstractApiHelper<NbaStatsLog> {
    constructor () {
        super('/api/nba-stats-logs')
    }
}

export const nbaStatsLogApiHelper = new NbaStatsLogApiHelper()
