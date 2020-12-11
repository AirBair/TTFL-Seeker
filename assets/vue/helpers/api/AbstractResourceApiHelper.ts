import axios, { AxiosResponse } from 'axios'
import AbstractResource from '../../models/api/AbstractResource'
import ResourceCollection from '../../models/api/ResourceCollection'
import * as QueryString from 'qs'

export default class AbstractResourceApiHelper<T extends AbstractResource> {
    baseUri: string

    constructor (baseUri: string) {
        this.baseUri = baseUri
    }

    find (id: number): Promise<AxiosResponse<T>> {
        return axios.get<T>(this.baseUri + '/' + id)
    }

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    findAll (params: any = {}): Promise<AxiosResponse<ResourceCollection<T>>> {
        // Transform sort order from vuetify datatable format to api platform format.
        if (params.sortBy != null) {
            Object.assign(params, { order: { [params.sortBy[0]]: params.sortDesc[0] ? 'desc' : 'asc' } })
        }

        return axios.get<ResourceCollection<T>>(this.baseUri, {
            params,
            paramsSerializer: function (params) {
                return QueryString.stringify(params, { encode: false })
            }
        }).then((response) => {
            response.data = new ResourceCollection<T>(response.data['hydra:member'], response.data['hydra:totalItems'])

            return response
        })
    }
}
