import AbstractApiHelper from './AbstractApiHelper'
import { type NbaGame } from '../types/NbaGame'

class NbaGameApiHelper extends AbstractApiHelper<NbaGame> {
    constructor () {
        super('/api/nba-games')
    }
}

export const nbaGameApiHelper = new NbaGameApiHelper()
