<template>
    <v-container fluid>
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
        >
            <template v-slot:[`item.lastName`]="{ item }">
                <router-link :to="{ name: 'nba_player_profile', params: { nbaPlayerId: item.id } }" class="text-decoration-none">
                    {{ item.lastName }}
                </router-link>
            </template>
            <template v-slot:[`item.isInjured`]="{ item }">
                <v-chip v-if="item.isInjured" color="red" dark>
                    <v-icon>mdi-ambulance</v-icon>
                </v-chip>
            </template>
        </v-data-table>
    </v-container>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'
import { Location } from 'vue-router/types/router'
import DataTableHeaderInterface from '../models/DataTableHeaderInterface'
import DataTableOptionsInterface from '../models/DataTableOptionsInterface'
import { nbaPlayerModule } from '../helpers/store-accessor'
import NbaPlayer from '../models/api/NbaPlayer'

@Component
export default class NbaPlayersList extends Vue {
    @Prop({ type: String, default: '50' }) readonly itemsPerPage!: string
    @Prop({ type: String, default: '1' }) readonly page!: string
    @Prop({ type: String, default: 'averageFantasyPoints' }) readonly sortBy!: string
    @Prop({ type: String, default: 'desc' }) readonly sortOrder!: string

    dataTableOptions: DataTableOptionsInterface = {
        itemsPerPage: parseInt(this.itemsPerPage),
        page: parseInt(this.page),
        sortBy: [this.sortBy],
        sortDesc: [(this.sortOrder === 'desc')]
    }

    get dataTableHeaders (): DataTableHeaderInterface[] {
        return [
            { text: 'LastName', value: 'lastName' },
            { text: 'FirstName', value: 'firstName' },
            { text: 'Team', value: 'nbaTeam.fullName' },
            { text: 'Injured ?', value: 'isInjured' },
            { text: 'AVG Fantasy Points', value: 'averageFantasyPoints' },
            { text: 'Past Year Fantasy Points', value: 'pastYearFantasyPoints' }
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
                sortOrder: this.dataTableOptions.sortDesc[0] ? 'desc' : 'asc'
            }
        }
        if (JSON.stringify(location.query) !== JSON.stringify(this.$route.query)) {
            this.$router.push(location)
            nbaPlayerModule.findAll({
                ...this.dataTableOptions
            })
        }
    }

    created (): void {
        nbaPlayerModule.findAll({
            ...this.dataTableOptions
        })
    }
}
</script>
