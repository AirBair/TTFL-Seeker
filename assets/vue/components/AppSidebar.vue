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
