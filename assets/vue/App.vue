<template>
    <v-app>
        <app-sidebar />
        <app-navbar />
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="8">
                        <v-card elevation="0">
                            <v-card-text class="text-center">
                                <h1>&#128064; Coming Soon !</h1>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component } from 'vue-property-decorator'
import AppNavbar from './components/AppNavbar.vue'
import AppSidebar from './components/AppSidebar.vue'

@Component({
    components: {
        AppNavbar,
        AppSidebar
    }
})
export default class App extends Vue {
}
</script>
