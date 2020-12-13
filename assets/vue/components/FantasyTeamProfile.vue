<template>
    <v-container fluid>
        <v-skeleton-loader
            v-if="isLoading"
            type="card"
        />
        <v-card class="elevation-10" v-if="!isLoading">
            <v-card-title>
                {{ fantasyTeam.name }}
            </v-card-title>
            <v-card-subtitle>
                Fantasy Points: <strong>{{ (fantasyTeam.lastFantasyTeamRanking) ? fantasyTeam.lastFantasyTeamRanking.fantasyPoints : '-' }} pts</strong><br />
                Fantasy Rank: <strong>{{ (fantasyTeam.lastFantasyTeamRanking) ? fantasyTeam.lastFantasyTeamRanking.fantasyRank : '-' }}</strong>
            </v-card-subtitle>
            <v-card-text>
                <p class="text-center font-italic">Chart of points & ranking evolution coming soon !</p>
            </v-card-text>
        </v-card>
        <v-divider class="my-4"/>
        <v-card class="elevation-10">
            <v-card-title>Fantasy Users</v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="dataTableHeaders"
                    :items="fantasyUsers"
                    :server-items-length="nbFantasyUsers"
                    :options.sync="dataTableOptions"
                    :loading="isLoading"
                    :footer-props="{
                        itemsPerPageOptions: [10, 30, 50, 100]
                    }"
                    dense
                    fixed-header
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
            </v-card-text>
        </v-card>

    </v-container>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'
import DataTableHeaderInterface from '../models/DataTableHeaderInterface'
import DataTableOptionsInterface from '../models/DataTableOptionsInterface'
import { fantasyTeamModule, fantasyUserModule } from '../helpers/store-accessor'
import FantasyTeam from '../models/api/FantasyTeam'
import FantasyUser from '../models/api/FantasyUser'

@Component
export default class FantasyTeamProfile extends Vue {
    @Prop() readonly fantasyTeamId!: string

    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: 50,
        page: 1,
        sortBy: ['name'],
        sortDesc: [false]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Username', value: 'username' },
            { text: 'Fantasy Team', value: 'fantasyTeam.name' },
            { text: 'Fantasy Rank', value: 'lastFantasyUserRanking.fantasyRank', sortable: false },
            { text: 'Fantasy Points', value: 'lastFantasyUserRanking.fantasyPoints', sortable: false },
            { text: 'Last Pick', value: 'lastFantasyPick', sortable: false }
        ]
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        this.loadFantasyUsers()
    }

    get isLoading (): boolean {
        return fantasyTeamModule.isLoading
    }

    get fantasyTeam (): FantasyTeam | null {
        return fantasyTeamModule.currentItem
    }

    get nbFantasyUsers (): number {
        return fantasyUserModule.totalItems ?? 0
    }

    get fantasyUsers (): FantasyUser[] {
        return fantasyUserModule.items
    }

    async created (): Promise<void> {
        await fantasyTeamModule.find(this.fantasyTeamId)
        await this.loadFantasyUsers()
    }

    async loadFantasyUsers (): Promise<void> {
        if (this.fantasyTeam) {
            await fantasyUserModule.findAll({
                fantasyTeam: this.fantasyTeam['@id'],
                ...this.dataTableOptions
            })
        }
    }
}
</script>
