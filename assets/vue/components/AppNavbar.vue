<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useNavigationDrawerStore } from '../stores/navigation-drawer'

const route = useRoute()
const navigationDrawerStore = useNavigationDrawerStore()

const navbarLabel = computed(() => {
    return route.meta.navbarLabel ?? ''
})
</script>

<template>
    <v-app-bar
        app
        theme="dark"
    >
        <v-app-bar-nav-icon @click.stop="navigationDrawerStore.toggleDrawer()" />
        <v-toolbar-title>
            {{ navbarLabel }}
        </v-toolbar-title>
        <v-spacer />
    </v-app-bar>
</template>
