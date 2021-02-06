<template>
    <v-container fluid>
        <v-card class="pb-n2 sticky-card">
            <v-card-text>
                <v-row no-gutters>
                    <v-col class="col-6 text-center mb-n6 px-2">
                        <date-picker-input
                            label="Game Day"
                            :date.sync="gameDay"
                            :dense="true"
                            :outlined="true"
                        />
                    </v-col>
                    <v-col class="col-6 text-center mb-n6 px-2">
                        <v-autocomplete
                            label="Show Availability for Fantasy User"
                            hint="Start Typing to search"
                            v-model="fantasyUser"
                            :items="fantasyUsers"
                            :loading="isFantasyUsersLoading"
                            :search-input.sync="fantasyUserSearch"
                            item-text="username"
                            item-value="@id"
                            return-object
                            hide-no-data
                            hide-selected
                            dense
                            outlined
                            clearable
                        />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <v-skeleton-loader
            v-if="isNbaGamesLoading"
            type="card"
        />
        <v-alert
            v-if="!isNbaGamesLoading && !nbNbaGames"
            class="col-6 offset-3 mt-4 py-4"
            type="warning"
            border="top"
            colored-border
            elevation="2"
        >
            No matchs scheduled for this date !
        </v-alert>
        <div v-if="nbNbaGames">
            <h5 class="mt-6 text-h5 text-center">
                {{ nbNbaGames }} Matchs Scheduled
            </h5>
            <v-row v-if="!isNbaGamesLoading">
                <v-col v-for="nbaGame in nbaGames" :key="nbaGame.id" class="col-6">
                    <v-card class="px-2 text-center">
                        <v-card-text>
                            <v-row align="center">
                                <v-col>
                                    <img
                                        v-if="nbaGame.localNbaTeam && nbaGame.localNbaTeam.logoFilePath"
                                        :src="nbaGame.localNbaTeam.logoFilePath"
                                        alt="Team Logo"
                                        height="70"
                                        width="70"
                                    />
                                    <h3>{{ nbaGame.localNbaTeam.fullName }}</h3>
                                </v-col>
                                <v-col>
                                    <h2>{{ nbaGame.scheduledAt ? new Date(nbaGame.scheduledAt).toLocaleTimeString('fr-Fr').substr(0, 5) : ''}}</h2>
                                    <h5>{{ nbaGame.scheduledAt ? new Date(nbaGame.scheduledAt).toLocaleDateString('fr-Fr') : ''}}</h5>
                                </v-col>
                                <v-col>
                                    <img
                                        v-if="nbaGame.visitorNbaTeam && nbaGame.visitorNbaTeam.logoFilePath"
                                        :src="nbaGame.visitorNbaTeam.logoFilePath"
                                        alt="Team Logo"
                                        height="70"
                                        width="70"
                                    />
                                    <h3>{{ nbaGame.visitorNbaTeam.fullName }}</h3>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <h5 class="mt-6 mb-2 text-h5 text-center">
                Players of the Day
            </h5>
            <v-skeleton-loader
                v-if="isNbaPlayersLoading"
                type="card"
            />
            <list-filter
                v-if="!isNbaPlayersLoading"
                :initial-filters="filters"
                :initial-available-filters="availableFilters"
                :handle-filter="confirmFilters"
                :handle-reset="resetFilter"
                class="my-2"
            />
            <v-data-table
                v-if="!isNbaPlayersLoading"
                :headers="nbaPlayersDataTableHeaders"
                :items="nbaPlayers"
                :server-items-length="nbNbaPlayers"
                :options.sync="dataTableOptions"
                :loading="isNbaPlayersLoading"
                :footer-props="{
                    itemsPerPageOptions: [10, 30, 50, 100]
                }"
                fixed-header
                class="elevation-10"
            >
                <template v-slot:item="{ item }">
                    <tr :class="isPlayerLock(item) ? 'red lighten-3' : ''">
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
                                :src="require('../../img/exotic-league-logo.jpg')"
                                alt="Exotic League"
                                height="40"
                                width="40"
                            />
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </div>
    </v-container>
</template>

<style scoped>
.sticky-card {
    position: sticky;
    top: 70px;
    z-index: 10;
}
</style>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'
import { fantasyPickModule, fantasyUserModule, nbaGameModule, nbaPlayerModule } from '../helpers/store-accessor'
import NbaGame from '../models/api/NbaGame'
import DataTableHeaderInterface from '../models/DataTableHeaderInterface'
import NbaPlayer, { nbaPlayerAvailableFilters, NbaPlayerFiltersParams } from '../models/api/NbaPlayer'
import DataTableOptionsInterface from '../models/DataTableOptionsInterface'
import { Location } from 'vue-router/types/router'
import * as QueryString from 'qs'
import { cloneDeep, forEach, forOwn, isString } from 'lodash'
import { ResourceCollectionFilter } from '../models/api/ResourceCollection'
import ListFilter from './snippets/ListFilter.vue'
import DatePickerInput from './snippets/DatePickerInput.vue'
import FantasyUser from '../models/api/FantasyUser'
import * as moment from 'moment'
import FantasyPick from '../models/api/FantasyPick'

@Component({
    components: {
        DatePickerInput,
        ListFilter
    }
})
export default class PicksOfTheDay extends Vue {
    @Prop({ type: String, default: '50' }) readonly itemsPerPage!: string
    @Prop({ type: String, default: '1' }) readonly page!: string
    @Prop({ type: String, default: 'averageFantasyPoints' }) readonly sortBy!: string
    @Prop({ type: String, default: 'desc' }) readonly sortOrder!: string

    gameDay: string = new Date().toISOString().substr(0, 10)
    fantasyUser: FantasyUser | null = null
    fantasyUserSearch: string | null = null
    lockedFantasyPicks: FantasyPick[] = []
    filters = new NbaPlayerFiltersParams()
    availableFilters = nbaPlayerAvailableFilters
    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: parseInt(this.itemsPerPage),
        page: parseInt(this.page),
        sortBy: [this.sortBy],
        sortDesc: [(this.sortOrder === 'desc')]
    }

    get nbaPlayersDataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Team', value: 'nbaTeam.fullName', sortable: false },
            { text: 'LastName', value: 'lastName' },
            { text: 'FirstName', value: 'firstName' },
            { text: 'Injured ?', value: 'isInjured' },
            { text: 'AVG Fantasy Points', value: 'averageFantasyPoints' },
            { text: 'Past Year Fantasy Points', value: 'pastYearFantasyPoints' },
            { text: 'Allowed in Exotic League ?', value: 'isAllowedInExoticLeague' }
        ]
    }

    get isNbaGamesLoading (): boolean {
        return nbaGameModule.isLoading
    }

    get nbNbaGames (): number {
        return nbaGameModule.totalItems ?? 0
    }

    get nbaGames (): NbaGame[] {
        return nbaGameModule.items
    }

    get nbaTeamIds (): string[] {
        const nbaTeamIds: string[] = []
        this.nbaGames.forEach(function (nbaGame: NbaGame) {
            if (nbaGame && nbaGame.localNbaTeam && nbaGame.localNbaTeam['@id']) {
                nbaTeamIds.push(nbaGame.localNbaTeam['@id'])
            }
            if (nbaGame && nbaGame.visitorNbaTeam && nbaGame.visitorNbaTeam['@id']) {
                nbaTeamIds.push(nbaGame.visitorNbaTeam['@id'])
            }
        })

        return nbaTeamIds
    }

    get isNbaPlayersLoading (): boolean {
        return nbaPlayerModule.isLoading
    }

    get nbNbaPlayers (): number {
        return nbaPlayerModule.totalItems ?? 0
    }

    get nbaPlayers (): NbaPlayer[] {
        return nbaPlayerModule.items
    }

    get fantasyUsers (): FantasyUser[] {
        return fantasyUserModule.items
    }

    get isFantasyUsersLoading (): boolean {
        return fantasyUserModule.isLoading
    }

    async created (): Promise<void> {
        await this.loadNbaGames()
        await this.loadNbaPlayers()
    }

    @Watch('gameDay')
    async onGameDayChange (): Promise<void> {
        await this.loadNbaGames()
        await this.loadNbaPlayers()
        await this.loadLockedFantasyPicks()
    }

    @Watch('fantasyUserSearch', { deep: true })
    onFantasyUserSearchChange (): void {
        if (this.fantasyUser?.username !== this.fantasyUserSearch && this.fantasyUserSearch != null) {
            fantasyUserModule.findAll({
                username: this.fantasyUserSearch,
                order: { username: 'asc' }
            })
        }
    }

    @Watch('fantasyUser', { deep: true })
    async onFantasyUserChange (): Promise<void> {
        await this.loadLockedFantasyPicks()
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        const location: Location = {
            name: 'picks_of_the_day',
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
            this.loadNbaPlayers()
        }
    }

    async loadNbaGames (): Promise<void> {
        await nbaGameModule.findAll({
            'gameDay[after]': this.gameDay,
            'gameDay[before]': this.gameDay,
            order: {
                scheduledAt: 'asc'
            }
        })
    }

    async loadNbaPlayers (): Promise<void> {
        if (this.nbaTeamIds.length) {
            await nbaPlayerModule.findAll({
                nbaTeam: this.nbaTeamIds,
                ...this.dataTableOptions,
                ...this.filters
            })
        } else {
            nbaPlayerModule.clearItems()
        }
    }

    async loadLockedFantasyPicks (): Promise<void> {
        this.lockedFantasyPicks = []
        if (this.fantasyUser) {
            const fantasyPicks = await fantasyPickModule.findAll({
                fantasyUser: this.fantasyUser?.['@id'],
                'pickedAt[strictly_after]': moment(this.gameDay, 'YYYY-MM-DD').subtract(30, 'days').format('YYYY-MM-DD')
            })
            if (fantasyPicks) {
                this.lockedFantasyPicks = cloneDeep(fantasyPicks['hydra:member'])
            }
        }
    }

    isPlayerLock (nbaPlayer: NbaPlayer): boolean {
        return (this.lockedFantasyPicks.find(
            item => item.nbaPlayer && item.nbaPlayer['@id'] === nbaPlayer['@id']
        ) !== undefined)
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
