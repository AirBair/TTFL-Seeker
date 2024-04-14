<script setup lang="ts">
import { reactive } from 'vue'
import useCommonList from '../composables/useCommonList'
import { type CommonListProps } from '../types/CommonListProps'
import { FilterType } from '../types/enums/FilterType'
import { type Filter } from '../types/Filter'
import ListFilterToolbar from '../components/ListFilterToolbar.vue'
import { type FantasyTeam } from '../types/FantasyTeam'
import { fantasyTeamApiHelper } from '../api/FantasyTeamApiHelper'

const props = defineProps<CommonListProps>()

const dataTableHeaders = [
    { title: 'Name', key: 'name' },
    { title: 'Exotic Team ?', key: 'isExoticTeam' },
    { title: 'Fantasy Rank', key: 'fantasyRank' },
    { title: 'Fantasy Points', key: 'fantasyPoints' }
]

const availableFilters: Filter[] = reactive([{
    key: 'name',
    label: 'Name',
    type: FilterType.String,
    isActive: false,
    value: null
}, {
    key: 'isExoticTeam',
    label: 'Exotic Team',
    type: FilterType.Boolean,
    isActive: false,
    value: null
}, {
    key: 'isSynchronizationActive',
    label: 'Synchronization Active',
    type: FilterType.Boolean,
    isActive: true,
    value: 'true'
}, {
    key: 'fantasyPoints[gte]',
    label: 'Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null
}, {
    key: 'fantasyPoints[lte]',
    label: 'Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null
}, {
    key: 'fantasyRank[gte]',
    label: 'Fantasy Rank',
    type: FilterType.Number,
    isActive: false,
    value: null
}, {
    key: 'fantasyRank[lte]',
    label: 'Fantasy Rank',
    type: FilterType.Number,
    isActive: false,
    value: null
}])

const {
    options,
    isLoading,
    items,
    totalItems,
    loadItems,
    applyFilters,
    resetFilters
} = useCommonList<FantasyTeam>(
    fantasyTeamApiHelper,
    'fantasy_teams_list',
    props,
    availableFilters
)
</script>

<template>
    <v-container fluid>
        <ListFilterToolbar
            :apply-filters="applyFilters"
            :reset-filters="resetFilters"
            :filters="availableFilters"
        />
        <v-data-table-server
            v-model:page="options.page"
            v-model:items-per-page="options.itemsPerPage"
            v-model:sort-by="options.sortBy"
            :headers="dataTableHeaders"
            :items="items"
            :items-length="totalItems"
            :loading="isLoading"
            class="elevation-1 my-2"
            @update:options="loadItems"
        >
            <template #[`item.name`]="{ item }">
                <router-link
                    :to="{ name: 'fantasy_team_profile', params: { id: item.id } }"
                    class="text-decoration-none"
                >
                    {{ item.name }}
                </router-link>
            </template>
            <template #[`item.isExoticTeam`]="{ item }">
                <v-img
                    v-if="item.isExoticTeam"
                    :src="require('../../img/exotic-league-logo.jpg')"
                    alt="Exotic League"
                    height="40"
                    width="40"
                />
            </template>
        </v-data-table-server>
    </v-container>
</template>
