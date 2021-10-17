import AbstractResource from './AbstractResource'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'

export default class FantasyTeam extends AbstractResource {
    name?: string
    isExoticTeam?: boolean
    isSynchronizationActive?: boolean
    fantasyPoints?: number
    fantasyRank?: number
}

export class FantasyTeamFiltersParams {
    name?: string
    isExoticTeam?: boolean
    'fantasyPoints[gte]'?: number
    'fantasyPoints[lte]'?: number
    'fantasyRank[gte]'?: number
    'fantasyRank[lte]'?: number
}

export const fantasyTeamAvailableFilters: ResourceCollectionFilter[] = [{
    name: 'name',
    label: 'Name',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'isExoticTeam',
    label: 'Exotic Team',
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
