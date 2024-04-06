<script setup lang="ts">
import { reactive } from 'vue'
import useCommonList from '../composables/useCommonList'
import { type CommonListProps } from '../types/CommonListProps'
import { FilterType } from '../types/enums/FilterType'
import { type Filter } from '../types/Filter'
import ListFilterToolbar from '../components/ListFilterToolbar.vue'
import { type FantasyUser } from '../types/FantasyUser'
import { fantasyUserApiHelper } from '../api/FantasyUserApiHelper'

const props = defineProps<CommonListProps>()

const dataTableHeaders = [
    { title: 'Username', key: 'username' },
    { title: 'Fantasy Team', key: 'fantasyTeam.name' },
    { title: 'Exotic User ?', key: 'isExoticUser' },
    { title: 'Fantasy Rank', key: 'fantasyRank' },
    { title: 'Fantasy Points', key: 'fantasyPoints' },
    { title: 'Last Pick', key: 'lastFantasyPick', sortable: false }
]

const availableFilters: Filter[] = reactive([{
    key: 'username',
    label: 'Username',
    type: FilterType.String,
    isActive: false,
    value: null
}, {
    key: 'ttflId',
    label: 'TTFL ID',
    type: FilterType.String,
    isActive: false,
    value: null
}, {
    key: 'fantasyTeam.name',
    label: 'Fantasy Team',
    type: FilterType.String,
    isActive: false,
    value: null
}, {
    key: 'isExoticUser',
    label: 'Exotic User',
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
} = useCommonList<FantasyUser>(
    fantasyUserApiHelper,
    'fantasy_users_list',
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
            <template #[`item.username`]="{ item }">
                <router-link
                    :to="{ name: 'fantasy_user_profile', params: { id: item.id } }"
                    class="text-decoration-none"
                >
                    {{ item.username }}
                </router-link>
            </template>
            <template #[`item.fantasyTeam.name`]="{ item }">
                <router-link
                    v-if="item.fantasyTeam"
                    :to="{ name: 'fantasy_team_profile', params: { id: item.fantasyTeam.id } }"
                    class="text-decoration-none"
                >
                    {{ item.fantasyTeam.name }}
                </router-link>
            </template>
            <template #[`item.isExoticUser`]="{ item }">
                <v-img
                    v-if="item.isExoticUser"
                    :src="require('../../img/exotic-league-logo.jpg')"
                    alt="Exotic League"
                    height="40"
                    width="40"
                />
            </template>
            <template #[`item.lastFantasyPick`]="{ item }">
                <span v-if="item.lastFantasyPick && item.lastFantasyPick.nbaPlayer">
                    <router-link
                        :to="{ name: 'nba_player_profile', params: { id: item.lastFantasyPick.nbaPlayer.id } }"
                        class="text-decoration-none"
                    >
                        {{ item.lastFantasyPick.nbaPlayer.fullName }}
                    </router-link>
                    ({{ item.lastFantasyPick.fantasyPoints }} pts)
                </span>
                <span v-else>-</span>
            </template>
        </v-data-table-server>
    </v-container>
</template>
