<script setup lang="ts">
import { computed, type Ref, ref, watch } from 'vue'
import { type FantasyTeam } from '../../types/FantasyTeam'
import { fantasyTeamApiHelper } from '../../api/FantasyTeamApiHelper'

const props = defineProps<{
    fantasyTeam: FantasyTeam | null | undefined
    label?: string
}>()
const emits = defineEmits<{
    (e: 'update:fantasyTeam', value: FantasyTeam | null | undefined)
}>()

const isLoading = ref(false)
const search = ref('')
const items: Ref<FantasyTeam[]> = ref([])
const selectedItem = computed({
    get (): FantasyTeam | null | undefined {
        return props.fantasyTeam
    },
    set (value: FantasyTeam | null | undefined) {
        emits('update:fantasyTeam', value)
    }
})

watch(search, async (): Promise<void> => {
    if (search.value.length > 2) {
        isLoading.value = true
        const response = await fantasyTeamApiHelper.findAll({
            name: search.value,
            order: {
                name: 'asc'
            }
        })
        items.value = response.data['hydra:member']
        isLoading.value = false
    }
})
</script>

<template>
    <v-autocomplete
        v-model="selectedItem"
        v-model:search="search"
        :label="props.label"
        hint="Start Typing to search"
        :items="items"
        :loading="isLoading"
        item-title="name"
        item-value="@id"
        return-object
        hide-no-data
        hide-selected
    />
</template>
