import AbstractResource from './AbstractResource'
import NbaTeam from './NbaTeam'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'

export default class NbaPlayer extends AbstractResource {
    firstName?: string
    lastName?: string
    fullName?: string
    position?: string
    jersey?: number
    isInjured?: boolean
    nbaTeam?: NbaTeam
    averageFantasyPoints?: number
    pastYearFantasyPoints?: number
    isAllowedInExoticLeague?: boolean
    updatedAt?: string
}

export class NbaPlayerFiltersParams {
    firstName?: string
    lastName?: string
    fullName?: string
    position?: string
    jersey?: number
    isInjured?: boolean
    'nbaTeam.fullName'?: string
    'averageFantasyPoints[gte]'?: number
    'averageFantasyPoints[lte]'?: number
    'pastYearFantasyPoints[gte]'?: number
    'pastYearFantasyPoints[lte]'?: number
    isAllowedInExoticLeague?: boolean
}

export const nbaPlayerAvailableFilters: ResourceCollectionFilter[] = [{
    name: 'firstName',
    label: 'First Name',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'lastName',
    label: 'Last Name',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'fullName',
    label: 'Full Name',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'position',
    label: 'Position',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'jersey',
    label: 'Jersey No.',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'isInjured',
    label: 'Injured ?',
    type: ResourceFilterType.Boolean,
    enable: false
}, {
    name: 'nbaTeam.fullName',
    label: 'Nba Team',
    type: ResourceFilterType.String,
    enable: false
}, {
    name: 'averageFantasyPoints[gte]',
    label: 'AVG Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'averageFantasyPoints[lte]',
    label: 'AVG Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'pastYearFantasyPoints[gte]',
    label: 'Past Year Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'pastYearFantasyPoints[lte]',
    label: 'Past Year Fantasy Points',
    type: ResourceFilterType.Number,
    enable: false
}, {
    name: 'isAllowedInExoticLeague',
    label: 'Allowed in Exotic League ?',
    type: ResourceFilterType.Boolean,
    enable: false
}]
