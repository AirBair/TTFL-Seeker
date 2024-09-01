import AbstractApiHelper from './AbstractApiHelper'
import { type FantasyUser } from '../types/FantasyUser'

class FantasyUserApiHelper extends AbstractApiHelper<FantasyUser> {
    constructor() {
        super('/api/fantasy-users')
    }
}

export const fantasyUserApiHelper = new FantasyUserApiHelper()
