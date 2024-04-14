<script setup lang="ts">
import { computed } from 'vue'
import { type Filter } from '../types/Filter'
import { FilterType } from '../types/enums/FilterType'

const props = defineProps<{
    applyFilters: () => void
    resetFilters: () => void
    filters: Filter[]
}>()

const emits = defineEmits<(e: 'update:filters', value: Filter[]) => void>()

const filterType = FilterType

const availableFilters = computed({
    get () {
        return props.filters
    },
    set (value) {
        emits('update:filters', value)
    }
})

const activeFilters = computed(() => {
    return availableFilters.value.filter((v) => v.isActive)
})
</script>

<template>
    <v-toolbar>
        <v-spacer />
        <v-menu
            offset-y
            :close-on-content-click="false"
        >
            <template #activator="{ props: menu }">
                <v-btn
                    color="black"
                    prepend-icon="mdi-filter-plus"
                    v-bind="menu"
                >
                    Filters
                </v-btn>
            </template>
            <v-list dense>
                <v-list-item
                    v-for="filter in availableFilters"
                    :key="filter.key"
                >
                    <v-list-item-title>
                        <v-checkbox
                            v-model="filter.isActive"
                            density="compact"
                            hide-details
                        >
                            <template #label>
                                {{ filter.label }}
                                <v-icon v-if="filter.key.endsWith('[gte]') || filter.key.endsWith('[after]')">
                                    mdi-greater-than-or-equal
                                </v-icon>
                                <v-icon v-if="filter.key.endsWith('[lte]') || filter.key.endsWith('[before]')">
                                    mdi-less-than-or-equal
                                </v-icon>
                            </template>
                        </v-checkbox>
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-toolbar>
    <v-card
        v-if="activeFilters.length > 0"
        class="mt-4"
    >
        <v-card-text>
            <v-row>
                <v-col
                    v-for="filter in activeFilters"
                    :key="filter.key"
                    lg="6"
                    sm="12"
                >
                    <!-- Boolean Filter -->
                    <v-radio-group
                        v-if="filter.type === filterType.Boolean"
                        v-model="filter.value"
                        inline
                        prepend-icon="mdi-close-circle"
                        @click:prepend="filter.isActive = false"
                    >
                        <template #label>
                            <div>{{ filter.label }}</div>
                        </template>
                        <v-radio
                            label="Yes"
                            value="true"
                        />
                        <v-radio
                            label="No"
                            value="false"
                        />
                    </v-radio-group>

                    <!-- Choice Filter -->
                    <v-select
                        v-else-if="filter.type === filterType.Choice"
                        v-model="filter.value"
                        :label="filter.label"
                        :items="filter.choices ?? []"
                        multiple
                        chips
                        closable-chips
                        prepend-icon="mdi-close-circle"
                        @click:prepend="filter.isActive = false"
                        @keyup.enter="props.applyFilters"
                    />

                    <!-- Date Filter -->
                    <v-text-field
                        v-else-if="filter.type === filterType.Date"
                        v-model="filter.value"
                        type="date"
                        prepend-icon="mdi-close-circle"
                        @click:prepend="filter.isActive = false"
                        @keyup.enter="props.applyFilters"
                    >
                        <template #label>
                            {{ filter.label }}
                            <v-icon v-if="filter.key.endsWith('[after]')">
                                mdi-greater-than-or-equal
                            </v-icon>
                            <v-icon v-if="filter.key.endsWith('[before]')">
                                mdi-less-than-or-equal
                            </v-icon>
                        </template>
                    </v-text-field>

                    <!-- String Filter -->
                    <v-text-field
                        v-else
                        v-model="filter.value"
                        :label="filter.label"
                        prepend-icon="mdi-close-circle"
                        @click:prepend="filter.isActive = false"
                        @keyup.enter="props.applyFilters"
                    />
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-actions>
            <v-spacer />
            <v-btn
                color="info"
                prepend-icon="mdi-filter-check"
                variant="elevated"
                @click="props.applyFilters"
            >
                Filter
            </v-btn>
            <v-btn
                class="ml-2"
                variant="elevated"
                prepend-icon="mdi-filter-remove"
                @click="props.resetFilters"
            >
                Reset
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
