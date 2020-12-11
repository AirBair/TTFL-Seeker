<template>
    <v-app>
        <app-sidebar />
        <app-navbar />
        <v-main>
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

@Component({
    components: {
        NbaPlayersList,
        AppNavbar,
        AppSidebar
    }
})
export default class App extends Vue {
}
</script>
