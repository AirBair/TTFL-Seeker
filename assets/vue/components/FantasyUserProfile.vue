<template>
    <v-container fluid>
        <v-skeleton-loader
            v-if="isLoading"
            type="card"
        />
        <v-card class="elevation-10" v-if="!isLoading">
            <v-card-title>
                {{ fantasyUser.username }}
                <v-spacer />
                <v-img
                    v-if="fantasyUser.isExoticUser"
                    :src="require('../../img/exotic-league-logo.jpg').default"
                    alt="Exotic League"
                    max-height="40"
                    max-width="40"
                />
            </v-card-title>
            <v-card-subtitle>
                <router-link v-if="fantasyUser.fantasyTeam" :to="{ name: 'fantasy_team_profile', params: { fantasyTeamId: fantasyUser.fantasyTeam.id } }" class="text-decoration-none">
                    {{ fantasyUser.fantasyTeam.name }}
                </router-link>
                <span v-else>Free Agent</span><br />
                Fantasy Points: <strong>{{ fantasyUser.fantasyPoints }} pts</strong><br />
                Fantasy Rank: <strong>{{ fantasyUser.fantasyRank }}</strong>
            </v-card-subtitle>
            <v-card-text>
                <p class="text-center font-italic">Chart of points & ranking evolution coming soon !</p>
            </v-card-text>
        </v-card>
        <v-divider class="my-4"/>
        <v-card class="elevation-10">
            <v-card-title>Last Picks</v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="dataTableHeaders"
                    :items="fantasyPicks"
                    :server-items-length="nbFantasyPicks"
                    :options.sync="dataTableOptions"
                    :loading="isLoading"
                    :footer-props="{
                        itemsPerPageOptions: [10, 30, 50, 100]
                    }"
                    dense
                    fixed-header
                >
                    <template v-slot:[`item.pickedAt`]="{ item }">
                        {{ new Date(item.pickedAt).toLocaleDateString() }}
                    </template>
                    <template v-slot:[`item.nbaPlayer`]="{ item }">
                        <router-link :to="{ name: 'nba_player_profile', params: { nbaPlayerId: item.nbaPlayer.id } }" class="text-decoration-none">
                            {{ item.nbaPlayer.fullName }}
                        </router-link>
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
import { fantasyUserModule, fantasyPickModule } from '../helpers/store-accessor'
import FantasyUser from '../models/api/FantasyUser'
import FantasyPick from '../models/api/FantasyPick'

@Component
export default class FantasyUserProfile extends Vue {
    @Prop() readonly fantasyUserId!: string

    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: 50,
        page: 1,
        sortBy: ['pickedAt'],
        sortDesc: [true]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'Picked At', value: 'pickedAt' },
            { text: 'Nba Player', value: 'nbaPlayer' },
            { text: 'Fantasy Points', value: 'fantasyPoints' }
        ]
    }

    @Watch('dataTableOptions', { deep: true })
    onDataTableOptionsChange (): void {
        this.loadFantasyPicks()
    }

    get isLoading (): boolean {
        return fantasyUserModule.isLoading
    }

    get fantasyUser (): FantasyUser | null {
        return fantasyUserModule.currentItem
    }

    get nbFantasyPicks (): number {
        return fantasyPickModule.totalItems ?? 0
    }

    get fantasyPicks (): FantasyPick[] {
        return fantasyPickModule.items
    }

    async created (): Promise<void> {
        await fantasyUserModule.find(this.fantasyUserId)
        await this.loadFantasyPicks()
    }

    async loadFantasyPicks (): Promise<void> {
        if (this.fantasyUser) {
            await fantasyPickModule.findAll({
                fantasyUser: this.fantasyUser['@id'],
                ...this.dataTableOptions
            })
        }
    }
}
</script>
