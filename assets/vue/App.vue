<template>
    <v-app>
        <app-sidebar />
        <app-navbar />
        <v-main class="grey lighten-3">
            <router-view :key="$route.path" />
        </v-main>
    </v-app>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component } from 'vue-property-decorator'
import AppNavbar from './components/AppNavbar.vue'
import AppSidebar from './components/AppSidebar.vue'
import NbaPlayersList from './components/NbaPlayersList.vue'
import { appModule } from './helpers/store-accessor'

@Component({
    components: {
        NbaPlayersList,
        AppNavbar,
        AppSidebar
    }
})
export default class App extends Vue {
    created (): void {
        appModule.setNbaYear(window.nbaYear)
        appModule.setIsNbaPlayoff(window.isNbaPlayoffs)
    }
}
</script>
