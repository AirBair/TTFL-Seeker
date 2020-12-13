import AbstractResource from './AbstractResource'
import { ResourceCollectionFilter } from './ResourceCollection'
import { ResourceFilterType } from './ResourceFilterType'
import FantasyTeamRanking from './FantasyTeamRanking'

export default class FantasyTeam extends AbstractResource {
    name?: string
    lastFantasyTeamRanking?: FantasyTeamRanking
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
