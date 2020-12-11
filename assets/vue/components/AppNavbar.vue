<template>
    <v-app-bar app dark>
        <v-app-bar-nav-icon @click.stop="toggleSidebarDrawer()" />
        <v-toolbar-title>
            Coming Soon
        </v-toolbar-title>
    </v-app-bar>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component } from 'vue-property-decorator'
import { appModule } from '../helpers/store-accessor'

@Component
export default class AppNavbar extends Vue {
    toggleSidebarDrawer (): void {
        appModule.toggleSidebarDrawer()
    }
}
</script>
