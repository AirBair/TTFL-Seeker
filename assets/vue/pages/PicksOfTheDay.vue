<script setup lang="ts">
import { computed, onMounted, reactive, ref, type Ref, watch } from 'vue'
import { useNbaPeriod } from '../stores/nba-period'
import { type FantasyTeam } from '../types/FantasyTeam'
import { type FantasyUser } from '../types/FantasyUser'
import { type NbaGame } from '../types/NbaGame'
import { type NbaPlayer } from '../types/NbaPlayer'
import { type FantasyPick } from '../types/FantasyPick'
import { nbaGameApiHelper } from '../api/NbaGameApiHelper'
import { nbaPlayerApiHelper } from '../api/NbaPlayerApiHelper'
import { fantasyPickApiHelper } from '../api/FantasyPickApiHelper'
import moment from 'moment'
import FantasyTeamAutocomplete from '../components/form-inputs/FantasyTeamAutocomplete.vue'
import FantasyUserAutocomplete from '../components/form-inputs/FantasyUserAutocomplete.vue'
import logoExoticLeague from '../../img/exotic-league-logo.jpg'

const gameDay: Ref<string> = ref(new Date().toISOString().substring(0, 10))
const fantasyTeam: Ref<FantasyTeam | null> = ref(null)
const fantasyUser: Ref<FantasyUser | null> = ref(null)
const nbaGames: Ref<NbaGame[]> = ref([])
const totalNbaGames: Ref<number> = ref(0)
const isNbaGamesLoading = ref(true)
const nbaPlayers: Ref<NbaPlayer[]> = ref([])
const totalNbaPlayers: Ref<number> = ref(0)
const isNbaPlayersLoading = ref(true)
const lockedTeamFantasyPicks: Ref<FantasyPick[]> = ref([])
const lockedUserFantasyPicks: Ref<FantasyPick[]> = ref([])

const nbaPeriod = useNbaPeriod()

const nbaTeamIds = computed((): string[] => {
    const ids: string[] = []
    nbaGames.value.forEach(function (nbaGame: NbaGame) {
        if (nbaGame.localNbaTeam?.['@id'] != null) {
            ids.push(nbaGame.localNbaTeam['@id'])
        }
        if (nbaGame.visitorNbaTeam?.['@id'] != null) {
            ids.push(nbaGame.visitorNbaTeam['@id'])
        }
    })

    return ids
})

const extraLabel = computed((): string => {
    let label = ''
    if (fantasyTeam.value !== null) {
        label = 'Available for ' + (fantasyTeam.value.name ?? '')
    }
    else if (fantasyUser.value !== null && !nbaPeriod.isNbaPlayoffs) {
        label = 'Days left for ' + (fantasyUser.value.username ?? '')
    }

    return label
})

const dataTableHeaders = [
    { title: 'Team', key: 'nbaTeam.fullName', sortable: false },
    { title: 'LastName', key: 'lastName' },
    { title: 'FirstName', key: 'firstName' },
    { title: 'Injured ?', key: 'isInjured' },
    { title: 'AVG Fantasy Points', key: 'averageFantasyPoints' },
    { title: 'Past Year Fantasy Points', key: 'pastYearFantasyPoints' },
    { title: 'Allowed in Exotic League ?', key: 'isAllowedInExoticLeague' },
    { title: extraLabel, key: 'extra', sortable: false },
]
const dataTableOptions = reactive({
    page: 1,
    itemsPerPage: 50,
    sortBy: [{
        key: 'averageFantasyPoints',
        order: 'desc',
    }],
})

const nbDaysLockedForUser = (nbaPlayer: NbaPlayer): number => {
    let ndDaysLockedForUser = 0
    const lockedPick = lockedUserFantasyPicks.value.find(item => item.nbaPlayer != null && item.nbaPlayer['@id'] === nbaPlayer['@id'])
    if (lockedPick !== undefined) {
        ndDaysLockedForUser = nbaPeriod.isNbaPlayoffs
            ? Number.MAX_SAFE_INTEGER
            : 30 - moment(gameDay.value, 'YYYY-MM-DD').diff(moment(lockedPick.pickedAt, 'YYYY-MM-DD'), 'days')
    }

    return ndDaysLockedForUser
}

const nbAvailableInTeam = (nbaPlayer: NbaPlayer): number => {
    return 10 - lockedTeamFantasyPicks.value.filter(
        item => item.nbaPlayer != null && item.nbaPlayer['@id'] === nbaPlayer['@id'],
    ).length
}

const isPlayerLock = (nbaPlayer: NbaPlayer): boolean => {
    let locked = false
    if (fantasyTeam.value !== null) {
        locked = nbAvailableInTeam(nbaPlayer) <= 0
    }
    else if (fantasyUser.value !== null) {
        locked = nbDaysLockedForUser(nbaPlayer) > 0
    }

    return locked
}

const loadNbaGames = async (): Promise<void> => {
    isNbaGamesLoading.value = true
    const response = await nbaGameApiHelper.findAll({
        'gameDay[after]': gameDay.value,
        'gameDay[before]': gameDay.value,
        'order': {
            scheduledAt: 'asc',
        },
        'pagination': false,
    })
    nbaGames.value = response.data.member
    totalNbaGames.value = response.data.totalItems
    isNbaGamesLoading.value = false
}

const loadNbaPlayers = async (): Promise<void> => {
    isNbaPlayersLoading.value = true
    const response = await nbaPlayerApiHelper.findAll({
        page: dataTableOptions.page,
        itemsPerPage: dataTableOptions.itemsPerPage,
        order: {
            [dataTableOptions.sortBy[0]?.key]: dataTableOptions.sortBy[0]?.order,
        },
        nbaTeam: nbaTeamIds.value,
    })
    nbaPlayers.value = response.data.member
    totalNbaPlayers.value = response.data.totalItems
    isNbaPlayersLoading.value = false
}

const loadLockedTeamFantasyPicks = async (): Promise<void> => {
    lockedTeamFantasyPicks.value = []
    if (fantasyTeam.value !== null) {
        const response = await fantasyPickApiHelper.findAll({
            'fantasyUser.fantasyTeam': fantasyTeam.value['@id'],
            'season': nbaPeriod.nbaYear,
            'isPlayoffs': nbaPeriod.isNbaPlayoffs,
            'pickedAt[strictly_after]': nbaPeriod.isNbaPlayoffs
                ? undefined
                : moment(gameDay.value, 'YYYY-MM-DD').subtract(30, 'days').format('YYYY-MM-DD'),
            'order': {
                pickedAt: 'desc',
            },
            'pagination': false,
        })
        lockedTeamFantasyPicks.value = response.data.member
    }
}

const loadLockedUserFantasyPicks = async (): Promise<void> => {
    lockedUserFantasyPicks.value = []
    if (fantasyUser.value !== null) {
        const response = await fantasyPickApiHelper.findAll({
            'fantasyUser': fantasyUser.value['@id'],
            'season': nbaPeriod.nbaYear,
            'isPlayoffs': nbaPeriod.isNbaPlayoffs,
            'pickedAt[strictly_after]': nbaPeriod.isNbaPlayoffs
                ? undefined
                : moment(gameDay.value, 'YYYY-MM-DD').subtract(30, 'days').format('YYYY-MM-DD'),
            'order': {
                pickedAt: 'desc',
            },
            'pagination': false,
        })
        lockedUserFantasyPicks.value = response.data.member
    }
}

watch(gameDay, async (): Promise<void> => {
    await loadNbaGames()
    await loadNbaPlayers()
    await loadLockedTeamFantasyPicks()
    await loadLockedUserFantasyPicks()
})

watch(fantasyTeam, async (): Promise<void> => {
    await loadLockedTeamFantasyPicks()
})

watch(fantasyUser, async (): Promise<void> => {
    await loadLockedUserFantasyPicks()
})

onMounted(async () => {
    await loadNbaGames()
    await loadNbaPlayers()
})
</script>

<template>
    <v-container fluid>
        <v-card class="sticky-card">
            <v-card-text>
                <v-row no-gutters>
                    <v-col class="px-2">
                        <v-text-field
                            v-model="gameDay"
                            label="Game Day"
                            type="date"
                        />
                    </v-col>
                    <v-col class="px-2">
                        <FantasyTeamAutocomplete
                            v-model:fantasy-team="fantasyTeam"
                            label="Show Availability for Fantasy Team"
                        />
                    </v-col>
                    <v-col class="px-2">
                        <FantasyUserAutocomplete
                            v-model:fantasy-user="fantasyUser"
                            label="Show Availability for Fantasy User"
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
            v-if="!isNbaGamesLoading && !totalNbaGames"
            class="mt-4"
            type="warning"
            border="top"
            colored-border
            elevation="2"
        >
            No games scheduled for this date !
        </v-alert>
        <div v-if="totalNbaGames">
            <h5 class="mt-6 mb-2 text-h5 text-center">
                {{ totalNbaGames }} Games Scheduled
            </h5>
            <v-row v-if="!isNbaGamesLoading">
                <v-col
                    v-for="nbaGame in nbaGames"
                    :key="nbaGame.id"
                    cols="6"
                >
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
                                    >
                                    <h3>{{ nbaGame.localNbaTeam?.fullName }}</h3>
                                </v-col>
                                <v-col>
                                    <h2>{{ nbaGame.scheduledAt ? new Date(nbaGame.scheduledAt).toLocaleTimeString('fr-Fr').substring(0, 5) : '' }}</h2>
                                    <h5>{{ nbaGame.scheduledAt ? new Date(nbaGame.scheduledAt).toLocaleDateString('fr-Fr') : '' }}</h5>
                                </v-col>
                                <v-col>
                                    <img
                                        v-if="nbaGame.visitorNbaTeam && nbaGame.visitorNbaTeam.logoFilePath"
                                        :src="nbaGame.visitorNbaTeam.logoFilePath"
                                        alt="Team Logo"
                                        height="70"
                                        width="70"
                                    >
                                    <h3>{{ nbaGame.visitorNbaTeam?.fullName }}</h3>
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
            <v-data-table-server
                v-model:page="dataTableOptions.page"
                v-model:items-per-page="dataTableOptions.itemsPerPage"
                v-model:sort-by="dataTableOptions.sortBy"
                :headers="dataTableHeaders"
                :items="nbaPlayers"
                :items-length="totalNbaPlayers"
                :loading="isNbaPlayersLoading"
                class="elevation-1 my-2"
                @update:options="loadNbaPlayers"
            >
                <template #item="{ item }">
                    <tr :class="isPlayerLock(item) ? 'bg-red-lighten-3' : ''">
                        <td>
                            <img
                                v-if="item.nbaTeam && item.nbaTeam.logoFilePath"
                                :src="item.nbaTeam.logoFilePath"
                                alt="Team Logo"
                                height="50"
                                width="50"
                            >
                        </td>
                        <td colspan="2">
                            <router-link
                                :to="{ name: 'nba_player_profile', params: { id: item.id } }"
                                class="text-decoration-none text-body-1"
                            >
                                {{ item.lastName }} {{ item.firstName }}
                            </router-link>
                            <br>
                            <span class="text-caption">{{ (item.nbaTeam) ? item.nbaTeam.fullName : 'Free Agent' }}</span>
                        </td>
                        <td>
                            <v-chip
                                v-if="item.isInjured"
                                color="red"
                                dark
                            >
                                <v-icon>mdi-ambulance</v-icon>
                            </v-chip>
                        </td>
                        <td>{{ item.averageFantasyPoints }}</td>
                        <td>{{ item.pastYearFantasyPoints }}</td>
                        <td>
                            <v-img
                                v-if="item.isAllowedInExoticLeague"
                                :src="logoExoticLeague"
                                alt="Exotic League"
                                height="40"
                                width="40"
                            />
                        </td>
                        <td>
                            <span v-if="fantasyTeam">
                                {{ nbAvailableInTeam(item) }}
                            </span>
                            <span v-else-if="fantasyUser && !nbaPeriod.isNbaPlayoffs">
                                {{ nbDaysLockedForUser(item) }}
                            </span>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
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
