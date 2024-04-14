<script setup lang="ts">
import { onMounted } from 'vue'
import AppSidebar from './components/AppSidebar.vue'
import AppNavbar from './components/AppNavbar.vue'
import { useNbaPeriod } from './stores/nba-period'

const nbaPeriod = useNbaPeriod()
onMounted(() => {
    nbaPeriod.setNbaYear(window.nbaYear)
    nbaPeriod.setIsNbaPlayoff(window.isNbaPlayoffs)
})
</script>

<template>
    <v-app>
        <AppSidebar />
        <AppNavbar />
        <v-main>
            <router-view />
        </v-main>
    </v-app>
</template>
