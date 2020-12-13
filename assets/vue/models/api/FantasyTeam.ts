import AbstractResource from './AbstractResource'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'

export default class FantasyTeam extends AbstractResource {
    name?: string
}

export class FantasyTeamFiltersParams {
    name?: string
}

export const fantasyTeamAvailableFilters: ResourceCollectionFilter[] = [{
    name: 'name',
    label: 'Name',
    type: ResourceFilterType.String,
    enable: false
}]
