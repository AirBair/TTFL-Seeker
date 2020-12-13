import AbstractResourceApiHelper from './AbstractResourceApiHelper'
import FantasyPick from '../../models/api/FantasyPick'

export default class FantasyPickApiHelper extends AbstractResourceApiHelper<FantasyPick> {
    constructor () {
        super('/api/fantasy-picks')
    }
}
