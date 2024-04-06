<script setup lang="ts">
import { onMounted, reactive, ref, type Ref } from 'vue'
import { cloneDeep } from 'lodash'
import { type NbaPlayer } from '../types/NbaPlayer'
import { type NbaStatsLog } from '../types/NbaStatsLog'
import { nbaPlayerApiHelper } from '../api/NbaPlayerApiHelper'
import { nbaStatsLogApiHelper } from '../api/NbaStatsLogApiHelper'

const props = defineProps<{
    id: number
}>()
const nbaPlayer: Ref<NbaPlayer> = ref({})
const totalNbaStatsLogs = ref(0)
const nbaStatsLogs: Ref<NbaStatsLog[]> = ref([])
const isLoading = ref(true)

const dataTableHeaders = [
    { title: 'Game Day', key: 'nbaGame.gameDay' },
    { title: 'Against', key: 'againstNbaTeam.logoFilePath', sortable: false },
    { title: '', key: 'againstNbaTeam.fullName', sortable: false },
    { title: 'Fantasy Points', key: 'fantasyPoints' },
    { title: 'Minutes Played', key: 'minutesPlayed' },
    { title: 'Best Pick ?', key: 'isBestPick' }
]
const dataTableOptions = reactive({
    page: 1,
    itemsPerPage: 50,
    sortBy: [{
        key: 'nbaGame.gameDay',
        order: 'desc'
    }]
})

const loadNbaPlayer = async (): Promise<void> => {
    const response = await nbaPlayerApiHelper.find(props.id)
    nbaPlayer.value = cloneDeep(response.data)
}

const loadNbaStatLogs = async (): Promise<void> => {
    const response = await nbaStatsLogApiHelper.findAll({
        page: dataTableOptions.page,
        itemsPerPage: dataTableOptions.itemsPerPage,
        order: {
            [dataTableOptions.sortBy[0]?.key]: dataTableOptions.sortBy[0]?.order
        },
        nbaPlayer: nbaPlayer.value['@id']
    })
    nbaStatsLogs.value = response.data['hydra:member']
    totalNbaStatsLogs.value = response.data['hydra:totalItems']
}

onMounted(async () => {
    await loadNbaPlayer()
    await loadNbaStatLogs()
    isLoading.value = false
})
</script>

<template>
    <v-container fluid>
        <v-skeleton-loader
            v-if="isLoading"
            type="card"
        />
        <v-card
            v-if="!isLoading"
            class="elevation-10"
        >
            <v-card-title>
                {{ nbaPlayer.fullName }}
                &nbsp;
                <a
                    :href="'https://www.nba.com/player/'+nbaPlayer.id"
                    target="_blank"
                >
                    <v-icon>mdi-open-in-new</v-icon>
                </a>
                <v-spacer />
                <v-img
                    v-if="nbaPlayer.isAllowedInExoticLeague"
                    :src="require('../../img/exotic-league-logo.jpg')"
                    alt="Exotic League"
                    max-height="40"
                    max-width="40"
                />
            </v-card-title>
            <v-card-subtitle>
                {{ (nbaPlayer.nbaTeam) ? nbaPlayer.nbaTeam.fullName : 'Free Agent' }}
                <span v-if="nbaPlayer.position">({{ nbaPlayer.position }})</span><br>
                Average Fantasy Points: <strong>{{ nbaPlayer.averageFantasyPoints }}</strong><br>
                Past Year Fantasy Points: <strong>{{ nbaPlayer.pastYearFantasyPoints }}</strong>
            </v-card-subtitle>
            <v-card-text>
                <p class="text-center font-italic">
                    Chart of points per match coming soon !
                </p>
            </v-card-text>
        </v-card>
        <v-divider class="my-4" />
        <v-card
            v-if="!isLoading"
            class="elevation-10"
        >
            <v-card-title>Last Games</v-card-title>
            <v-card-text>
                <v-data-table-server
                    v-model:page="dataTableOptions.page"
                    v-model:items-per-page="dataTableOptions.itemsPerPage"
                    v-model:sort-by="dataTableOptions.sortBy"
                    :headers="dataTableHeaders"
                    :items="nbaStatsLogs"
                    :items-length="totalNbaStatsLogs"
                    :loading="isLoading"
                    class="elevation-1 my-2"
                    @update:options="loadNbaStatLogs"
                >
                    <template #[`item.nbaGame.gameDay`]="{ item }">
                        {{ new Date(item.nbaGame.gameDay).toLocaleDateString() }}
                    </template>
                    <template #[`item.againstNbaTeam.logoFilePath`]="{ item }">
                        <img
                            :src="(item.nbaTeam.id === item.nbaGame.localNbaTeam.id) ? item.nbaGame.visitorNbaTeam.logoFilePath : item.nbaGame.localNbaTeam.logoFilePath"
                            alt="Team Logo"
                            height="50"
                            width="50"
                        >
                    </template>
                    <template #[`item.againstNbaTeam.fullName`]="{ item }">
                        {{ (item.nbaTeam.id === item.nbaGame.localNbaTeam.id) ? item.nbaGame.visitorNbaTeam.fullName : item.nbaGame.localNbaTeam.fullName }}
                    </template>
                    <template #[`item.isBestPick`]="{ item }">
                        <v-icon
                            v-if="item.isBestPick"
                            small
                            color="red"
                        >
                            mdi-star
                        </v-icon>
                    </template>
                </v-data-table-server>
            </v-card-text>
        </v-card>
    </v-container>
</template>
