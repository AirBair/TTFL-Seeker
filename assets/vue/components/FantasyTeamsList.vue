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
            :items="fantasyTeams"
            :server-items-length="nbFantasyTeams"
            :options.sync="dataTableOptions"
            :loading="isLoading"
            :footer-props="{
                itemsPerPageOptions: [10, 30, 50, 100]
            }"
            fixed-header
            class="elevation-10"
        >
            <template v-slot:[`item.name`]="{ item }">
                <router-link :to="{ name: 'fantasy_team_profile', params: { fantasyTeamId: item.id } }" class="text-decoration-none">
                    {{ item.name }}
                </router-link>
            </template>
            <template v-slot:[`item.isExoticTeam`]="{ item }">
                <v-img
                    v-if="item.isExoticTeam"
                    :src="require('../../img/exotic-league-logo.jpg')"
                    alt="Exotic League"
                    height="40"
                    width="40"
                />
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
import { fantasyTeamModule } from '../helpers/store-accessor'
import FantasyTeam, { fantasyTeamAvailableFilters, FantasyTeamFiltersParams } from '../models/api/FantasyTeam'
import { ResourceCollectionFilter } from '../models/api/ResourceCollection'
import * as QueryString from 'qs'
import { forEach, forOwn, isString } from 'lodash'

@Component({
    components: {
        ListFilter
    }
})

export default class FantasyTeamsList extends Vue {
    @Prop({ type: String, default: '50' }) readonly itemsPerPage!: string
    @Prop({ type: String, default: '1' }) readonly page!: string
    @Prop({ type: String, default: 'username' }) readonly sortBy!: string
    @Prop({ type: String, default: 'asc' }) readonly sortOrder!: string

    filters = new FantasyTeamFiltersParams()
    availableFilters = fantasyTeamAvailableFilters
    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: parseInt(this.itemsPerPage),
        page: parseInt(this.page),
        sortBy: [this.sortBy],
        sortDesc: [(this.sortOrder === 'desc')]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Name', value: 'name' },
            { text: 'Exotic Team ?', value: 'isExoticTeam' },
            { text: 'Fantasy Rank', value: 'fantasyRank' },
            { text: 'Fantasy Points', value: 'fantasyPoints' }
        ]
    }

    get isLoading (): boolean {
        return fantasyTeamModule.isLoading
    }

    get nbFantasyTeams (): number {
        return fantasyTeamModule.totalItems ?? 0
    }

    get fantasyTeams (): FantasyTeam[] {
        return fantasyTeamModule.items
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        const location: Location = {
            name: 'fantasy_teams_list',
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
            fantasyTeamModule.findAll({
                ...this.dataTableOptions,
                ...this.filters,
                isSynchronizationActive: true
            })
        }
    }

    created (): void {
        this.initFilters()
        fantasyTeamModule.findAll({
            ...this.dataTableOptions,
            ...this.filters,
            isSynchronizationActive: true
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
        this.filters = new FantasyTeamFiltersParams()
        this.onDataTableOptionsChange()
    }
}
</script>
