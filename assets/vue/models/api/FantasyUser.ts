import AbstractResource from './AbstractResource'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'
import FantasyTeam from './FantasyTeam'
import FantasyPick from './FantasyPick'

export default class FantasyUser extends AbstractResource {
    username?: string
    ttflId?: number
    fantasyTeam?: FantasyTeam
    isExoticUser?: boolean
    fantasyPoints?: number
    fantasyRank?: number
    lastFantasyPick?: FantasyPick
}

export class FantasyUserFiltersParams {
    username?: string
    ttflId?: string
    'fantasyTeam.name'?: string
    isExoticUser?: boolean
    'fantasyPoints[gte]'?: number
    'fantasyPoints[lte]'?: number
    'fantasyRank[gte]'?: number
    'fantasyRank[lte]'?: number
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
}, {
    name: 'isExoticUser',
    label: 'Exotic User',
    type: ResourceFilterType.Boolean,
    enable: false
}, {
    name: 'fantasyPoints[gte]',
    label: 'Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'fantasyPoints[lte]',
    label: 'Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'fantasyRank[gte]',
    label: 'Fantasy Rank',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'fantasyRank[lte]',
    label: 'Fantasy Rank',
    type: ResourceFilterType.Number,
    enable: false
}]
