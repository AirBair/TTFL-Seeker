import { defineStore } from 'pinia'
import { ref } from 'vue'
export const useNbaPeriod = defineStore('nba-period', () => {
    const nbaYear = ref(new Date().getFullYear())
    const isNbaPlayoffs = ref(false)

    function setNbaYear (year: number): void {
        nbaYear.value = year
    }

    function setIsNbaPlayoff (isPlayoffs: boolean): void {
        isNbaPlayoffs.value = isPlayoffs
    }

    return { nbaYear, isNbaPlayoffs, setNbaYear, setIsNbaPlayoff }
})
