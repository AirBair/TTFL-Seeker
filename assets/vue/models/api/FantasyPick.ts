import AbstractResource from './AbstractResource'
import NbaPlayer from './NbaPlayer'
import FantasyUser from './FantasyUser'

export default class FantasyPick extends AbstractResource {
    season?: number
    isPlayoffs?: boolean
    pickedAt?: string
    fantasyUser?: FantasyUser
    nbaPlayer?: NbaPlayer
    fantasyPoints?: number
}
