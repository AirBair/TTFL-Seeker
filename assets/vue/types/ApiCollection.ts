import { type ApiResource } from './ApiResource'

export interface ApiCollection<T extends ApiResource> {
    'hydra:member': T[]
    'hydra:totalItems': number
}
