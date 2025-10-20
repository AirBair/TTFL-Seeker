<script setup lang="ts">
import { useNavigationDrawerStore } from '../stores/navigation-drawer'
import { useNbaPeriod } from '../stores/nba-period'
import logoNbaPlayoffs from '../../img/logo-nba-playoffs.png'

const navigationDrawerStore = useNavigationDrawerStore()
const nbaPeriod = useNbaPeriod()
</script>

<template>
    <v-navigation-drawer
        v-model="navigationDrawerStore.isOpen"
        theme="dark"
    >
        <v-list>
            <v-list-item>
                <template #title>
                    <div class="font-weight-bold text-center text-wrap">
                        TTFL Seeker
                    </div>
                </template>
            </v-list-item>
        </v-list>
        <v-list
            nav
            density="compact"
        >
            <v-list-item
                :to="{ name: 'picks_of_the_day' }"
                title="Picks of the Day"
                prepend-icon="mdi-crosshairs"
            />
            <v-list-subheader>NBA</v-list-subheader>
            <v-list-item
                :to="{ name: 'nba_players_list' }"
                title="NBA Players"
                prepend-icon="mdi-account-group"
            />
            <v-list-subheader>Fantasy</v-list-subheader>
            <v-list-item
                :to="{ name: 'fantasy_users_list' }"
                title="Fantasy Users"
                prepend-icon="mdi-account-group"
            />
            <v-list-item
                :to="{ name: 'fantasy_teams_list' }"
                title="Fantasy Teams"
                prepend-icon="mdi-bookmark"
            />
            <v-list-subheader>Restricted Area</v-list-subheader>
            <v-list-item
                href="/admin"
                title="Administration"
                prepend-icon="mdi-lock"
            />
        </v-list>
        <template #append>
            <p class="text-center text-overline text-amber">
                NBA API Year: {{ nbaPeriod.nbaYear }}
            </p>
            <div
                v-if="nbaPeriod.isNbaPlayoffs"
                class="py-2 bg-grey-lighten-3"
            >
                <v-img :src="logoNbaPlayoffs" />
            </div>
        </template>
    </v-navigation-drawer>
</template>
