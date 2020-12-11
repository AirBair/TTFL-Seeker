import AbstractResource from './AbstractResource'

export default class ResourceCollection<T extends AbstractResource> {
    'hydra:member': T[]
    'hydra:totalItems': number

    constructor (items: T[], totalItems: number) {
        this['hydra:member'] = items
        this['hydra:totalItems'] = totalItems
    }
}
