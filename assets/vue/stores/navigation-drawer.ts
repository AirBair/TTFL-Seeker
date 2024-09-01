import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useNavigationDrawerStore = defineStore('navigation-drawer', () => {
    const isOpen = ref(true)
    function toggleDrawer(): void {
        isOpen.value = !isOpen.value
    }

    return { isOpen, toggleDrawer }
})
