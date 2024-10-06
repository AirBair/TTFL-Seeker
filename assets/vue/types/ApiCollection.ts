import { type ApiResource } from './ApiResource'

export interface ApiCollection<T extends ApiResource> {
    member: T[]
    totalItems: number
}
