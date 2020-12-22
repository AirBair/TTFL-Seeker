<template>
    <v-container fluid>
        <list-filter
            :initial-filters="filters"
            :initial-available-filters="availableFilters"
            :handle-filter="confirmFilters"
            :handle-reset="resetFilter"
            class="my-2"
        />
        <v-data-table
            :headers="dataTableHeaders"
            :items="nbaPlayers"
            :server-items-length="nbNbaPlayers"
            :options.sync="dataTableOptions"
            :loading="isLoading"
            :footer-props="{
                itemsPerPageOptions: [10, 30, 50, 100]
            }"
            fixed-header
            class="elevation-10"
        >
            <template v-slot:item="{ item }">
                <tr>
                    <td>
                        <img
                            v-if="item.nbaTeam && item.nbaTeam.logoFilePath"
                            :src="item.nbaTeam.logoFilePath"
                            alt="Team Logo"
                            height="50"
                            width="50"
                        />
                    </td>
                    <td colspan="2">
                        <router-link :to="{ name: 'nba_player_profile', params: { nbaPlayerId: item.id } }" class="text-decoration-none text-body-1">
                            {{ item.lastName }} {{ item.firstName }}
                        </router-link>
                        <br />
                        <span class="text-caption">{{ (item.nbaTeam) ? item.nbaTeam.fullName : 'Free Agent' }}</span>
                    </td>
                    <td>
                        <v-chip v-if="item.isInjured" color="red" dark>
                            <v-icon>mdi-ambulance</v-icon>
                        </v-chip>
                    </td>
                    <td>{{ item.averageFantasyPoints }}</td>
                    <td>{{ item.pastYearFantasyPoints }}</td>
                    <td>
                        <v-img
                            v-if="item.isAllowedInExoticLeague"
                            :src="require('../../img/exotic-league-logo.jpg').default"
                            alt="Exotic League"
                            height="40"
                            width="40"
                        />
                    </td>
                </tr>
            </template>
        </v-data-table>
    </v-container>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'
import { Location } from 'vue-router/types/router'
import ListFilter from './snippets/ListFilter.vue'
import DataTableHeaderInterface from '../models/DataTableHeaderInterface'
import DataTableOptionsInterface from '../models/DataTableOptionsInterface'
import { nbaPlayerModule } from '../helpers/store-accessor'
import NbaPlayer, { nbaPlayerAvailableFilters, NbaPlayerFiltersParams } from '../models/api/NbaPlayer'
import { ResourceCollectionFilter } from '../models/api/ResourceCollection'
import * as QueryString from 'qs'
import { forEach, forOwn, isString } from 'lodash'

@Component({
    components: {
        ListFilter
    }
})

export default class NbaPlayersList extends Vue {
    @Prop({ type: String, default: '50' }) readonly itemsPerPage!: string
    @Prop({ type: String, default: '1' }) readonly page!: string
    @Prop({ type: String, default: 'averageFantasyPoints' }) readonly sortBy!: string
    @Prop({ type: String, default: 'desc' }) readonly sortOrder!: string

    filters = new NbaPlayerFiltersParams()
    availableFilters = nbaPlayerAvailableFilters
    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: parseInt(this.itemsPerPage),
        page: parseInt(this.page),
        sortBy: [this.sortBy],
        sortDesc: [(this.sortOrder === 'desc')]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Team', value: 'nbaTeam.fullName' },
            { text: 'LastName', value: 'lastName' },
            { text: 'FirstName', value: 'firstName' },
            { text: 'Injured ?', value: 'isInjured' },
            { text: 'AVG Fantasy Points', value: 'averageFantasyPoints' },
            { text: 'Past Year Fantasy Points', value: 'pastYearFantasyPoints' },
            { text: 'Allowed in Exotic League ?', value: 'isAllowedInExoticLeague' }
        ]
    }

    get isLoading (): boolean {
        return nbaPlayerModule.isLoading
    }

    get nbNbaPlayers (): number {
        return nbaPlayerModule.totalItems ?? 0
    }

    get nbaPlayers (): NbaPlayer[] {
        return nbaPlayerModule.items
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        const location: Location = {
            name: 'nba_players_list',
            query: {
                itemsPerPage: this.dataTableOptions.itemsPerPage.toString(),
                page: this.dataTableOptions.page.toString(),
                sortBy: this.dataTableOptions.sortBy[0],
                sortOrder: this.dataTableOptions.sortDesc[0] ? 'desc' : 'asc',
                filters: QueryString.stringify(this.filters, { encode: false })
            }
        }
        if (JSON.stringify(location.query) !== JSON.stringify(this.$route.query)) {
            this.$router.push(location)
            nbaPlayerModule.findAll({
                ...this.dataTableOptions,
                ...this.filters
            })
        }
    }

    created (): void {
        this.initFilters()
        nbaPlayerModule.findAll({
            ...this.dataTableOptions,
            ...this.filters
        })
    }

    initFilters (): void {
        if (this.$route.query.filters && isString(this.$route.query.filters)) {
            this.filters = QueryString.parse(this.$route.query.filters, { depth: false })
            forOwn(this.filters, (filterValue, filterName: string) => {
                forEach(this.availableFilters, function (availableFilter: ResourceCollectionFilter) {
                    if (availableFilter.name === filterName) {
                        availableFilter.value = filterValue
                        availableFilter.enable = true
                    }
                })
            })
        }
    }

    confirmFilters (): void {
        this.onDataTableOptionsChange()
    }

    resetFilter (): void {
        this.filters = new NbaPlayerFiltersParams()
        this.onDataTableOptionsChange()
    }
}
</script>
