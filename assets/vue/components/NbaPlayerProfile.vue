<template>
    <v-container fluid>
        <v-card>
            <v-card-title>{{ nbaPlayer.fullName }}</v-card-title>
            <v-card-subtitle>
                {{ nbaPlayer.nbaTeam.fullName }}
                <span v-if="nbaPlayer.position">({{nbaPlayer.position}})</span>
            </v-card-subtitle>
            <v-card-text>
                Average Fantasy Points: {{ nbaPlayer.averageFantasyPoints }}<br />
                Past Year Fantasy Points: {{ nbaPlayer.pastYearFantasyPoints }}
            </v-card-text>
        </v-card>
        <v-divider class="my-4"/>
        <v-card>
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
                    dense
                    fixed-header
                >
                    <template v-slot:[`item.nbaGame.gameDay`]="{ item }">
                        {{ new Date(item.nbaGame.gameDay).toLocaleDateString() }}
                    </template>
                    <template v-slot:[`item.against`]="{ item }">
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
            { text: 'Against', value: 'against', sortable: false },
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
