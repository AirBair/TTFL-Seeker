import AbstractApiHelper from './AbstractApiHelper'
import { type FantasyPick } from '../types/FantasyPick'

class FantasyPickApiHelper extends AbstractApiHelper<FantasyPick> {
    constructor () {
        super('/api/fantasy-picks')
    }
}

export const fantasyPickApiHelper = new FantasyPickApiHelper()
