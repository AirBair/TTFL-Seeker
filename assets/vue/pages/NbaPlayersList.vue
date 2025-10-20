<script setup lang="ts">
import { reactive } from 'vue'
import useCommonList from '../composables/useCommonList'
import { type CommonListProps } from '../types/CommonListProps'
import { FilterType } from '../types/enums/FilterType'
import { type Filter } from '../types/Filter'
import ListFilterToolbar from '../components/ListFilterToolbar.vue'
import { type NbaPlayer } from '../types/NbaPlayer'
import { nbaPlayerApiHelper } from '../api/NbaPlayerApiHelper'
import logoExoticLeague from '../../img/exotic-league-logo.jpg'

const props = withDefaults(defineProps<CommonListProps>(), {
    sortBy: 'averageFantasyPoints',
    sortOrder: 'desc',
})

const dataTableHeaders = [
    { title: 'Team', key: 'nbaTeam.fullName' },
    { title: 'LastName', key: 'lastName' },
    { title: 'FirstName', key: 'firstName' },
    { title: 'Injured ?', key: 'isInjured' },
    { title: 'AVG Fantasy Points', key: 'averageFantasyPoints' },
    { title: 'Past Year Fantasy Points', key: 'pastYearFantasyPoints' },
    { title: 'Allowed in Exotic League ?', key: 'isAllowedInExoticLeague' },
]

const availableFilters: Filter[] = reactive([{
    key: 'firstName',
    label: 'First Name',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'lastName',
    label: 'Last Name',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'fullName',
    label: 'Full Name',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'position',
    label: 'Position',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'jersey',
    label: 'Jersey No.',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'isInjured',
    label: 'Injured ?',
    type: FilterType.Boolean,
    isActive: false,
    value: null,
}, {
    key: 'nbaTeam.fullName',
    label: 'Nba Team',
    type: FilterType.String,
    isActive: false,
    value: null,
}, {
    key: 'averageFantasyPoints[gte]',
    label: 'AVG Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null,
}, {
    key: 'averageFantasyPoints[lte]',
    label: 'AVG Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null,
}, {
    key: 'pastYearFantasyPoints[gte]',
    label: 'Past Year Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null,
}, {
    key: 'pastYearFantasyPoints[lte]',
    label: 'Past Year Fantasy Points',
    type: FilterType.Number,
    isActive: false,
    value: null,
}, {
    key: 'isAllowedInExoticLeague',
    label: 'Allowed in Exotic League ?',
    type: FilterType.Boolean,
    isActive: false,
    value: null,
}])

const {
    options,
    isLoading,
    items,
    totalItems,
    loadItems,
    applyFilters,
    resetFilters,
} = useCommonList<NbaPlayer>(
    nbaPlayerApiHelper,
    'nba_players_list',
    props,
    availableFilters,
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
            <template #item="{ item }">
                <tr>
                    <td>
                        <img
                            v-if="item.nbaTeam && item.nbaTeam.logoFilePath"
                            :src="item.nbaTeam.logoFilePath"
                            alt="Team Logo"
                            height="50"
                            width="50"
                        >
                    </td>
                    <td colspan="2">
                        <router-link
                            :to="{ name: 'nba_player_profile', params: { id: item.id } }"
                            class="text-decoration-none text-body-1"
                        >
                            {{ item.lastName }} {{ item.firstName }}
                        </router-link>
                        <br>
                        <span class="text-caption">{{ (item.nbaTeam) ? item.nbaTeam.fullName : 'Free Agent' }}</span>
                    </td>
                    <td>
                        <v-chip
                            v-if="item.isInjured"
                            color="red"
                            dark
                        >
                            <v-icon>mdi-ambulance</v-icon>
                        </v-chip>
                    </td>
                    <td>{{ item.averageFantasyPoints }}</td>
                    <td>{{ item.pastYearFantasyPoints }}</td>
                    <td>
                        <v-img
                            v-if="item.isAllowedInExoticLeague"
                            :src="logoExoticLeague"
                            alt="Exotic League"
                            height="40"
                            width="40"
                        />
                    </td>
                </tr>
            </template>
        </v-data-table-server>
    </v-container>
</template>
