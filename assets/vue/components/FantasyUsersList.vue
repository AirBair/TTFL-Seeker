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
            :items="fantasyUsers"
            :server-items-length="nbFantasyUsers"
            :options.sync="dataTableOptions"
            :loading="isLoading"
            :footer-props="{
                itemsPerPageOptions: [10, 30, 50, 100]
            }"
            fixed-header
            class="elevation-10"
        >
            <template v-slot:[`item.username`]="{ item }">
                <router-link :to="{ name: 'fantasy_user_profile', params: { fantasyUserId: item.id } }" class="text-decoration-none">
                    {{ item.username }}
                </router-link>
            </template>
            <template v-slot:[`item.lastFantasyUserRanking.fantasyRank`]="{ item }">
                <span v-if="item.lastFantasyUserRanking">
                    {{ item.lastFantasyUserRanking.fantasyRank }}
                </span>
                <span v-else>-</span>
            </template>
            <template v-slot:[`item.lastFantasyUserRanking.fantasyPoints`]="{ item }">
                <span v-if="item.lastFantasyUserRanking">
                    {{ item.lastFantasyUserRanking.fantasyPoints }} pts
                </span>
                <span v-else>-</span>
            </template>
            <template v-slot:[`item.lastFantasyPick`]="{ item }">
                <span v-if="item.lastFantasyPick && item.lastFantasyPick.nbaPlayer">
                    <router-link :to="{ name: 'nba_player_profile', params: { nbaPlayerId: item.lastFantasyPick.nbaPlayer.id } }" class="text-decoration-none">
                        {{ item.lastFantasyPick.nbaPlayer.fullName }}
                    </router-link>
                    ({{ item.lastFantasyPick.fantasyPoints }} pts)
                </span>
                <span v-else>-</span>
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
import { fantasyUserModule } from '../helpers/store-accessor'
import FantasyUser, { fantasyUserAvailableFilters, FantasyUserFiltersParams } from '../models/api/FantasyUser'
import { ResourceCollectionFilter } from '../models/api/ResourceCollection'
import * as QueryString from 'qs'
import { forEach, forOwn, isString } from 'lodash'

@Component({
    components: {
        ListFilter
    }
})

export default class FantasyUsersList extends Vue {
    @Prop({ type: String, default: '50' }) readonly itemsPerPage!: string
    @Prop({ type: String, default: '1' }) readonly page!: string
    @Prop({ type: String, default: 'username' }) readonly sortBy!: string
    @Prop({ type: String, default: 'asc' }) readonly sortOrder!: string

    filters = new FantasyUserFiltersParams()
    availableFilters = fantasyUserAvailableFilters
    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: parseInt(this.itemsPerPage),
        page: parseInt(this.page),
        sortBy: [this.sortBy],
        sortDesc: [(this.sortOrder === 'desc')]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Username', value: 'username' },
            { text: 'Fantasy Team', value: 'fantasyTeam.name' },
            { text: 'Fantasy Rank', value: 'lastFantasyUserRanking.fantasyRank' },
            { text: 'Fantasy Points', value: 'lastFantasyUserRanking.fantasyPoints' },
            { text: 'Last Pick', value: 'lastFantasyPick' }
        ]
    }

    get isLoading (): boolean {
        return fantasyUserModule.isLoading
    }

    get nbFantasyUsers (): number {
        return fantasyUserModule.totalItems ?? 0
    }

    get fantasyUsers (): FantasyUser[] {
        return fantasyUserModule.items
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        const location: Location = {
            name: 'fantasy_users_list',
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
            fantasyUserModule.findAll({
                ...this.dataTableOptions,
                ...this.filters
            })
        }
    }

    created (): void {
        this.initFilters()
        fantasyUserModule.findAll({
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
        this.filters = new FantasyUserFiltersParams()
        this.onDataTableOptionsChange()
    }
}
</script>
