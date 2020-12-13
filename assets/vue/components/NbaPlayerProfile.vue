<template>
    <v-container fluid>
        <v-skeleton-loader
            v-if="isLoading"
            type="card"
        />
        <v-card class="elevation-10" v-if="!isLoading">
            <v-card-title>
                {{ nbaPlayer.fullName }}
                <v-spacer />
                <v-img
                    v-if="nbaPlayer.isAllowedInExoticLeague"
                    :src="require('../../img/exotic-league-logo.jpg').default"
                    alt="Exotic League"
                    max-height="40"
                    max-width="40"
                />
            </v-card-title>
            <v-card-subtitle>
                {{ (nbaPlayer.nbaTeam) ? nbaPlayer.nbaTeam.fullName : 'Free Agent' }}
                <span v-if="nbaPlayer.position">({{nbaPlayer.position}})</span><br />
                Average Fantasy Points: <strong>{{ nbaPlayer.averageFantasyPoints }}</strong><br />
                Past Year Fantasy Points: <strong>{{ nbaPlayer.pastYearFantasyPoints }}</strong>
            </v-card-subtitle>
            <v-card-text>
                <p class="text-center font-italic">Chart of points per match coming soon !</p>
            </v-card-text>
        </v-card>
        <v-divider class="my-4"/>
        <v-card class="elevation-10">
            <v-card-title>Last Games</v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="dataTableHeaders"
                    :items="nbaStatsLogs"
                    :server-items-length="nbNbaStatsLogs"
                    :options.sync="dataTableOptions"
                    :loading="isLoading"
                    :footer-props="{
                        itemsPerPageOptions: [10, 30, 50, 100]
                    }"
                    fixed-header
                >
                    <template v-slot:[`item.nbaGame.gameDay`]="{ item }">
                        {{ new Date(item.nbaGame.gameDay).toLocaleDateString() }}
                    </template>
                    <template v-slot:[`item.againstNbaTeam.logoFilePath`]="{ item }">
                        <img
                            :src="(item.nbaTeam.id === item.nbaGame.localNbaTeam.id) ? item.nbaGame.visitorNbaTeam.logoFilePath : item.nbaGame.localNbaTeam.logoFilePath"
                            alt="Team Logo"
                            height="50"
                            width="50"
                        />
                    </template>
                    <template v-slot:[`item.againstNbaTeam.fullName`]="{ item }">
                        {{ (item.nbaTeam.id === item.nbaGame.localNbaTeam.id) ? item.nbaGame.visitorNbaTeam.fullName : item.nbaGame.localNbaTeam.fullName }}
                    </template>
                    <template v-slot:[`item.isBestPick`]="{ item }">
                        <v-icon v-if="item.isBestPick" small color="red">mdi-star</v-icon>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

    </v-container>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'
import DataTableHeaderInterface from '../models/DataTableHeaderInterface'
import DataTableOptionsInterface from '../models/DataTableOptionsInterface'
import { nbaPlayerModule, nbaStatsLogModule } from '../helpers/store-accessor'
import NbaPlayer from '../models/api/NbaPlayer'
import NbaStatsLog from '../models/api/NbaStatsLog'

@Component
export default class NbaPlayerProfile extends Vue {
    @Prop() readonly nbaPlayerId!: string

    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: 50,
        page: 1,
        sortBy: ['nbaGame.gameDay'],
        sortDesc: [true]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Game Day', value: 'nbaGame.gameDay' },
            { text: 'Against', value: 'againstNbaTeam.logoFilePath', sortable: false },
            { text: '', value: 'againstNbaTeam.fullName', sortable: false },
            { text: 'Fantasy Points', value: 'fantasyPoints' },
            { text: 'Minutes Played', value: 'minutesPlayed' },
            { text: 'Best Pick ?', value: 'isBestPick' }
        ]
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        this.loadNbaStatsLogs()
    }

    get isLoading (): boolean {
        return nbaPlayerModule.isLoading
    }

    get nbaPlayer (): NbaPlayer | null {
        return nbaPlayerModule.currentItem
    }

    get nbNbaStatsLogs (): number {
        return nbaStatsLogModule.totalItems ?? 0
    }

    get nbaStatsLogs (): NbaStatsLog[] {
        return nbaStatsLogModule.items
    }

    async created (): Promise<void> {
        await nbaPlayerModule.find(this.nbaPlayerId)
        await this.loadNbaStatsLogs()
    }

    async loadNbaStatsLogs (): Promise<void> {
        if (this.nbaPlayer) {
            await nbaStatsLogModule.findAll({
                nbaPlayer: this.nbaPlayer['@id'],
                ...this.dataTableOptions
            })
        }
    }
}
</script>
