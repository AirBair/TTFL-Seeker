import AbstractResource from './AbstractResource'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'
import FantasyTeam from './FantasyTeam'
import FantasyPick from './FantasyPick'
import FantasyUserRanking from './FantasyUserRanking'

export default class FantasyUser extends AbstractResource {
    username?: string
    ttflId?: number
    fantasyTeam?: FantasyTeam
    lastFantasyPick?: FantasyPick
    lastFantasyUserRanking?: FantasyUserRanking
}

export class FantasyUserFiltersParams {
    username?: string
    ttflId?: string
    'fantasyTeam.name'?: string
}

export const fantasyUserAvailableFilters: ResourceCollectionFilter[] = [{
    name: 'username',
    label: 'Username',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'ttflId',
    label: 'TTFL ID',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'fantasyTeam.name',
    label: 'Fantasy Team',
    type: ResourceFilterType.String,
    enable: false
}]
