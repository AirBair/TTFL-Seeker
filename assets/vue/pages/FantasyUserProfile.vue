<script setup lang="ts">
import { onMounted, reactive, ref, type Ref } from 'vue'
import { cloneDeep } from 'lodash'
import { type FantasyUser } from '../types/FantasyUser'
import { type FantasyPick } from '../types/FantasyPick'
import { fantasyUserApiHelper } from '../api/FantasyUserApiHelper'
import { fantasyPickApiHelper } from '../api/FantasyPickApiHelper'

const props = defineProps<{
    id: number
}>()
const fantasyUser: Ref<FantasyUser> = ref({})
const totalFantasyPicks = ref(0)
const fantasyPicks: Ref<FantasyPick[]> = ref([])
const isLoading = ref(true)

const dataTableHeaders = [
    { title: 'Picked At', key: 'pickedAt' },
    { title: 'Nba Player', key: 'nbaPlayer' },
    { title: 'Fantasy Points', key: 'fantasyPoints' },
    { title: 'No Pick ?', key: 'isNoPick' }
]
const dataTableOptions = reactive({
    page: 1,
    itemsPerPage: 50,
    sortBy: [{
        key: 'pickedAt',
        order: 'desc'
    }]
})

const loadFantasyUser = async (): Promise<void> => {
    const response = await fantasyUserApiHelper.find(props.id)
    fantasyUser.value = cloneDeep(response.data)
}

const loadFantasyPicks = async (): Promise<void> => {
    const response = await fantasyPickApiHelper.findAll({
        page: dataTableOptions.page,
        itemsPerPage: dataTableOptions.itemsPerPage,
        order: {
            [dataTableOptions.sortBy[0]?.key]: dataTableOptions.sortBy[0]?.order
        },
        fantasyUser: fantasyUser.value['@id']
    })
    fantasyPicks.value = response.data['hydra:member']
    totalFantasyPicks.value = response.data['hydra:totalItems']
}

onMounted(async () => {
    await loadFantasyUser()
    await loadFantasyPicks()
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
                {{ fantasyUser.username }}
                &nbsp;
                <a
                    :href="'https://fantasy.trashtalk.co/?tpl=halloffame&ttpl='+fantasyUser.ttflId"
                    target="_blank"
                >
                    <v-icon>mdi-open-in-new</v-icon>
                </a>
                <v-spacer />
                <v-img
                    v-if="fantasyUser.isExoticUser"
                    :src="require('../../img/exotic-league-logo.jpg')"
                    alt="Exotic League"
                    max-height="40"
                    max-width="40"
                />
            </v-card-title>
            <v-card-subtitle>
                <router-link
                    v-if="fantasyUser.fantasyTeam"
                    :to="{ name: 'fantasy_team_profile', params: { id: fantasyUser.fantasyTeam.id } }"
                    class="text-decoration-none"
                >
                    {{ fantasyUser.fantasyTeam?.name }}
                </router-link>
                <span v-else>Free Agent</span><br>
                Fantasy Points: <strong>{{ fantasyUser.fantasyPoints }} pts</strong><br>
                Fantasy Rank: <strong>{{ fantasyUser.fantasyRank }}</strong>
            </v-card-subtitle>
            <v-card-text>
                <p class="text-center font-italic">
                    Chart of points & ranking evolution coming soon !
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
                    :items="fantasyPicks"
                    :items-length="totalFantasyPicks"
                    :loading="isLoading"
                    class="elevation-1 my-2"
                    @update:options="loadFantasyPicks"
                >
                    <template #[`item.pickedAt`]="{ item }">
                        {{ new Date(item.pickedAt).toLocaleDateString() }}
                    </template>
                    <template #[`item.nbaPlayer`]="{ item }">
                        <router-link
                            v-if="item.nbaPlayer"
                            :to="{ name: 'nba_player_profile', params: { nbaPlayerId: item.nbaPlayer.id } }"
                            class="text-decoration-none"
                        >
                            {{ item.nbaPlayer.fullName }}
                        </router-link>
                    </template>
                    <template #[`item.isNoPick`]="{ item }">
                        <v-icon v-if="item.isNoPick">
                            mdi-alert-circle
                        </v-icon>
                    </template>
                </v-data-table-server>
            </v-card-text>
        </v-card>
    </v-container>
</template>
