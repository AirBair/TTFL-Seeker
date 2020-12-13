<template>
    <v-navigation-drawer app :mini-variant="!isOpenSidebarDrawer" dark>
        <v-list>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="font-weight-bold text-center text-wrap">
                        {{ appName }}
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-list dense nav subheader>
            <v-subheader>Nba</v-subheader>
            <v-list-item :to="{ name: 'nba_players_list' }">
                <v-list-item-icon>
                    <v-icon>mdi-account-group</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>NBA Players</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-list dense nav subheader>
            <v-subheader>Fantasy</v-subheader>
            <v-list-item :to="{ name: 'fantasy_users_list' }">
                <v-list-item-icon>
                    <v-icon>mdi-account-group</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Fantasy Users</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item :to="{ name: 'fantasy_teams_list' }">
                <v-list-item-icon>
                    <v-icon>mdi-bookmark</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Fantasy Teams</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component } from 'vue-property-decorator'
import { appModule } from '../helpers/store-accessor'

@Component
export default class AppSidebar extends Vue {
    get appName (): string {
        return appModule.appName
    }

    get isOpenSidebarDrawer (): boolean {
        return appModule.isOpenSidebarDrawer
    }
}
</script>
