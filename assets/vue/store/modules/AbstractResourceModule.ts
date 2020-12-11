import { Action, Mutation, VuexModule } from 'vuex-module-decorators'
import { Module } from 'vuex'
import ResourceCollection from '../../models/api/ResourceCollection'
import AbstractResource from '../../models/api/AbstractResource'
import AbstractResourceApiHelper from '../../helpers/api/AbstractResourceApiHelper'

export interface AbstractResourceState<T> {
    isLoading: boolean;
    error: string | null;
    totalItems: number | null;
    items: T[];
    currentItem: T | null;
}

export default class AbstractApiResourceModule<T extends AbstractResource> extends VuexModule implements AbstractResourceState<T> {
    protected apiHelper: AbstractResourceApiHelper<T>
    isLoading = false
    error: string | null = null
    violations = {}
    totalItems: number | null = null
    items: T[] = []
    currentItem: T | null = null

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    constructor (module: Module<ThisType<any>, any>, apiHelper: AbstractResourceApiHelper<T>) {
        super(module)
        this.apiHelper = apiHelper
    }

    @Mutation
    fetchItemPending (): void {
        this.isLoading = true
        this.error = null
        this.violations = {}
        this.currentItem = null
    }

    @Mutation
    fetchItemSuccess (item: T): void {
        this.isLoading = false
        this.error = null
        this.violations = {}
        this.currentItem = item
    }

    @Mutation
    fetchItemError (error: string): void {
        this.isLoading = false
        this.error = error
        this.currentItem = null
    }

    @Action
    async find (id: number): Promise<T | null> {
        let response
        this.context.commit('fetchItemPending')
        try {
            response = await this.apiHelper.find(id)
            this.context.commit('fetchItemSuccess', response.data)
        } catch (error) {
            if (error.response && error.response.data['hydra:description']) {
                this.context.commit('fetchItemError', error.response.data['hydra:description'])
            } else {
                this.context.commit('fetchItemError', error)
            }
        }

        return response?.data ?? null
    }

    @Action
    async findOneBy (params: Record<string, unknown>): Promise<T | null> {
        let response
        let item
        this.context.commit('fetchItemPending')
        try {
            response = await this.apiHelper.findAll(params)
            item = (response.data['hydra:member'] && response.data['hydra:member'][0]) ? response.data['hydra:member'][0] : null
            this.context.commit('fetchItemSuccess', item)
        } catch (error) {
            if (error.response && error.response.data['hydra:description']) {
                this.context.commit('fetchItemError', error.response.data['hydra:description'])
            } else {
                this.context.commit('fetchItemError', error)
            }
        }

        return item ?? null
    }

    @Mutation
    fetchItemsPending (): void {
        this.isLoading = true
        this.error = null
        this.violations = {}
        this.totalItems = null
        this.items = []
    }

    @Mutation
    fetchItemsSuccess (resourceCollection: ResourceCollection<T>): void {
        this.isLoading = false
        this.error = null
        this.violations = {}
        this.totalItems = resourceCollection['hydra:totalItems']
        this.items = resourceCollection['hydra:member']
    }

    @Mutation
    fetchItemsError (error: string): void {
        this.isLoading = false
        this.error = error
        this.totalItems = null
        this.items = []
    }

    @Action
    async findAll (params: Record<string, unknown>): Promise<ResourceCollection<T> | null> {
        let response
        this.context.commit('fetchItemsPending')
        try {
            response = await this.apiHelper.findAll(params)
            this.context.commit('fetchItemsSuccess', response.data)
        } catch (error) {
            if (error.response && error.response.data['hydra:description']) {
                this.context.commit('fetchItemsError', error.response.data['hydra:description'])
            } else {
                this.context.commit('fetchItemsError', error)
            }
        }

        return response?.data ?? null
    }

    @Mutation
    clearItemsMutation (): void {
        this.items = []
    }

    @Action
    clearItems (): void {
        this.context.commit('clearItemsMutation')
    }
}
