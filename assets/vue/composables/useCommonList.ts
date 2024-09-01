import { ref, reactive, watch, onMounted, type Ref } from 'vue'
import { useRouter } from 'vue-router'
import * as QueryString from 'qs'
import { forEach, forOwn, isString } from 'lodash'
import type AbstractApiHelper from '../api/AbstractApiHelper'
import { type ApiResource } from '../types/ApiResource'
import { type CommonListProps } from '../types/CommonListProps'
import { type Filter } from '../types/Filter'

// eslint-disable-next-line @typescript-eslint/explicit-function-return-type
export default function useCommonList<T extends ApiResource>(
    api: AbstractApiHelper<T>,
    listRoute: string,
    props: CommonListProps,
    availableFilters: Filter[],
) {
    const router = useRouter()
    const isLoading = ref(true)
    const items: Ref<T[]> = ref([])
    const totalItems = ref(0)
    const options = reactive({
        page: parseInt(props.page ?? '1'),
        itemsPerPage: parseInt(props.itemsPerPage ?? '30'),
        sortBy: [{
            key: props.sortBy ?? 'name',
            order: props.sortOrder ?? 'asc',
        }],
        filters: {},
    })

    const loadItems = async (): Promise<void> => {
        isLoading.value = true
        const response = await api.findAll({
            page: options.page,
            itemsPerPage: options.itemsPerPage,
            order: {
                [options.sortBy[0]?.key]: options.sortBy[0]?.order,
            },
            ...options.filters,
        })
        items.value = response.data['hydra:member']
        totalItems.value = response.data['hydra:totalItems']
        isLoading.value = false
    }

    const convertAvailableFiltersToOptionsObject = (): Record<string, string | number | null> => {
        const obj = {}
        for (const filter of availableFilters) {
            if (filter.isActive) {
                obj[filter.key] = filter.value
            }
        }
        return obj
    }

    const initFilters = async (): Promise<void> => {
        if (isString(props.filters)) {
            const obj = QueryString.parse(props.filters, { depth: false })
            forOwn(obj, function (filterValue, filterName: string) {
                forEach(availableFilters, function (filter) {
                    if (filter.key === filterName && isString(filterValue)) {
                        filter.isActive = true
                        filter.value = filterValue
                    }
                })
            })
        }
        options.filters = convertAvailableFiltersToOptionsObject()
        await loadItems()
    }

    const applyFilters = async (): Promise<void> => {
        options.page = 1
        options.filters = convertAvailableFiltersToOptionsObject()
        await loadItems()
    }

    const resetFilters = async (): Promise<void> => {
        options.page = 1
        options.filters = {}
        availableFilters.forEach((filter) => {
            filter.isActive = false
            filter.value = null
        })
        await loadItems()
    }

    watch(options, async () => {
        await router.push({
            name: listRoute,
            query: {
                page: options.page,
                itemsPerPage: options.itemsPerPage,
                sortBy: options.sortBy[0]?.key,
                sortOrder: options.sortBy[0]?.order,
                filters: QueryString.stringify(options.filters),
            },
        })
    })

    watch(availableFilters, async () => {
        if (availableFilters.filter(v => v.isActive).length === 0) {
            await resetFilters()
        }
    })

    onMounted(async () => {
        await initFilters()
    })

    return {
        options,
        isLoading,
        items,
        totalItems,
        loadItems,
        initFilters,
        applyFilters,
        resetFilters,
    }
}
