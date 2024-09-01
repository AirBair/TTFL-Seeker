import axios, { type AxiosResponse } from 'axios'
import * as QueryString from 'qs'
import { type ApiCollection } from '../types/ApiCollection'
import { type ApiResource } from '../types/ApiResource'

export default class AbstractApiHelper<T extends ApiResource> {
    baseUri: string

    constructor(baseUri: string) {
        this.baseUri = baseUri
    }

    async find(id: number): Promise<AxiosResponse<T>> {
        return await axios.get<T>(`${this.baseUri}/${id.toString()}`)
    }

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    async findAll(params: any = {}): Promise<AxiosResponse<ApiCollection<T>>> {
        return await axios.get<ApiCollection<T>>(this.baseUri, {
            // eslint-disable-next-line @typescript-eslint/no-unsafe-assignment
            params,
            paramsSerializer: function (params) {
                return QueryString.stringify(params, { encode: false })
            },
        })
    }
}
