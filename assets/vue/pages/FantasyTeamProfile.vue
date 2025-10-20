<script setup lang="ts">
import { onMounted, reactive, ref, type Ref } from 'vue'
import { cloneDeep } from 'lodash'
import { type FantasyTeam } from '../types/FantasyTeam'
import { type FantasyUser } from '../types/FantasyUser'
import { fantasyTeamApiHelper } from '../api/FantasyTeamApiHelper'
import { fantasyUserApiHelper } from '../api/FantasyUserApiHelper'
import logoExoticLeague from '../../img/exotic-league-logo.jpg'

const props = defineProps<{
    id: number
}>()
const fantasyTeam: Ref<FantasyTeam> = ref({})
const totalFantasyUsers = ref(0)
const fantasyUsers: Ref<FantasyUser[]> = ref([])
const isLoading = ref(true)

const dataTableHeaders = [
    { title: 'Username', key: 'username' },
    { title: 'Fantasy Team', key: 'fantasyTeam.name' },
    { title: 'Fantasy Rank', key: 'fantasyRank' },
    { title: 'Fantasy Points', key: 'fantasyPoints' },
    { title: 'Last Pick', key: 'lastFantasyPick', sortable: false },
]
const dataTableOptions = reactive({
    page: 1,
    itemsPerPage: 50,
    sortBy: [{
        key: 'name',
        order: 'desc',
    }],
})

const loadFantasyTeam = async (): Promise<void> => {
    const response = await fantasyTeamApiHelper.find(props.id)
    fantasyTeam.value = cloneDeep(response.data)
}

const loadFantasyUsers = async (): Promise<void> => {
    const response = await fantasyUserApiHelper.findAll({
        page: dataTableOptions.page,
        itemsPerPage: dataTableOptions.itemsPerPage,
        order: {
            [dataTableOptions.sortBy[0]?.key]: dataTableOptions.sortBy[0]?.order,
        },
        fantasyTeam: fantasyTeam.value['@id'],
    })
    fantasyUsers.value = response.data.member
    totalFantasyUsers.value = response.data.totalItems
}

onMounted(async () => {
    await loadFantasyTeam()
    await loadFantasyUsers()
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
                {{ fantasyTeam.name }}
                &nbsp;
                <a
                    :href="'https://fantasy.trashtalk.co/?tpl=equipe&team='+fantasyTeam.name"
                    target="_blank"
                >
                    <v-icon>mdi-open-in-new</v-icon>
                </a>
                <v-spacer />
                <v-img
                    v-if="fantasyTeam.isExoticTeam"
                    :src="logoExoticLeague"
                    alt="Exotic League"
                    max-height="40"
                    max-width="40"
                />
            </v-card-title>
            <v-card-subtitle>
                Fantasy Points: <strong>{{ fantasyTeam.fantasyPoints }} pts</strong><br>
                Fantasy Rank: <strong>{{ fantasyTeam.fantasyRank }}</strong>
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
                    :items="fantasyUsers"
                    :items-length="totalFantasyUsers"
                    :loading="isLoading"
                    class="elevation-1 my-2"
                    @update:options="loadFantasyUsers"
                >
                    <template #[`item.username`]="{ item }">
                        <router-link
                            :to="{ name: 'fantasy_user_profile', params: { id: item.id } }"
                            class="text-decoration-none"
                        >
                            {{ item.username }}
                        </router-link>
                    </template>
                    <template #[`item.lastFantasyPick`]="{ item }">
                        <span v-if="item.lastFantasyPick && item.lastFantasyPick.nbaPlayer">
                            <router-link
                                :to="{ name: 'nba_player_profile', params: { id: item.lastFantasyPick.nbaPlayer.id } }"
                                class="text-decoration-none"
                            >
                                {{ item.lastFantasyPick.nbaPlayer.fullName }}
                            </router-link>
                            ({{ item.lastFantasyPick.fantasyPoints }} pts)
                        </span>
                        <span v-else>-</span>
                    </template>
                </v-data-table-server>
            </v-card-text>
        </v-card>
    </v-container>
</template>
