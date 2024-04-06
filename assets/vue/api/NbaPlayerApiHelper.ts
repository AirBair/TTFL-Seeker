import AbstractApiHelper from './AbstractApiHelper'
import { type NbaPlayer } from '../types/NbaPlayer'

class NbaPlayerApiHelper extends AbstractApiHelper<NbaPlayer> {
    constructor () {
        super('/api/nba-players')
    }
}

export const nbaPlayerApiHelper = new NbaPlayerApiHelper()
