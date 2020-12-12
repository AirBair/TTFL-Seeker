import AbstractResource from './AbstractResource'
import { ResourceFilterType } from './ResourceFilterType'

export default class ResourceCollection<T extends AbstractResource> {
    'hydra:member': T[]
    'hydra:totalItems': number

    constructor (items: T[], totalItems: number) {
        this['hydra:member'] = items
        this['hydra:totalItems'] = totalItems
    }
}

export interface ResourceCollectionFilter {
    name: string;
    label: string;
    type: ResourceFilterType;
    enable: boolean;
    pickerMenu?: boolean;
    value?: string | number | boolean;
    choices?: { label: string, value: string | number }[];
}
