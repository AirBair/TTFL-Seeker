<script setup lang="ts">
import { computed, type Ref, ref, watch } from 'vue'
import { type FantasyUser } from '../../types/FantasyUser'
import { fantasyUserApiHelper } from '../../api/FantasyUserApiHelper'

const props = defineProps<{
    fantasyUser: FantasyUser | null | undefined
    label?: string
}>()
const emits = defineEmits<{
    (e: 'update:fantasyUser', value: FantasyUser | null | undefined)
}>()

const isLoading = ref(false)
const search = ref('')
const items: Ref<FantasyUser[]> = ref([])
const selectedItem = computed({
    get(): FantasyUser | null | undefined {
        return props.fantasyUser
    },
    set(value: FantasyUser | null | undefined) {
        emits('update:fantasyUser', value)
    },
})

watch(search, async (): Promise<void> => {
    if (search.value.length > 2) {
        isLoading.value = true
        const response = await fantasyUserApiHelper.findAll({
            username: search.value,
            order: {
                username: 'asc',
            },
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
        item-title="username"
        item-value="@id"
        return-object
        hide-no-data
        hide-selected
    />
</template>
