<template>
    <v-card>
        <v-container fluid class="text-right">
            <v-menu offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn text v-on="on">
                        <v-icon>
                            mdi-filter-plus
                        </v-icon>
                        Filters
                    </v-btn>
                </template>
                <v-list dense>
                    <v-list-item
                        v-for="(filter, index) in disableFilters"
                        :key="index"
                        @click="toggleFilter(filter.name)"
                    >
                        <v-list-item-title>
                            {{ filter.label }}
                            <v-icon v-if="filter.name.endsWith('[gte]') || filter.name.endsWith('[after]')" dense>mdi-greater-than-or-equal</v-icon>
                            <v-icon v-if="filter.name.endsWith('[lte]') || filter.name.endsWith('[before]')" dense>mdi-less-than-or-equal</v-icon>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-container>
        <v-card-text v-if="enabledFilters.length > 0">
            <v-row>
                <v-col
                    v-for="(filter, index) in enabledFilters"
                    v-bind:key="index"
                    sm="12" class="py-0"
                >
                    <!-- Number Filter -->
                    <v-text-field
                        v-if="filter.type === enumFilterType.Number"
                        v-model.number="filters[[filter.name]]"
                        :label="filter.label"
                        prepend-icon="mdi-close-circle"
                        @click:prepend="toggleFilter(filter.name)"
                        @keyup.enter.native="handleFilter"
                        outlined
                        dense
                    >
                        <template v-slot:label>
                            {{ filter.label }}
                            <v-icon dense v-if="filter.name.endsWith('[gte]') || filter.name.endsWith('[after]')">mdi-greater-than-or-equal</v-icon>
                            <v-icon dense v-if="filter.name.endsWith('[lte]') || filter.name.endsWith('[before]')">mdi-less-than-or-equal</v-icon>
                        </template>
                    </v-text-field>

                    <!-- Date Filter -->
                    <v-menu
                        v-else-if="filter.type === enumFilterType.Date"
                        v-model="filter.pickerMenu"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-on="on"
                                v-model="filters[[filter.name]]"
                                :label="filter.label"
                                prepend-icon="mdi-close-circle"
                                @click:prepend="toggleFilter(filter.name)"
                                @keyup.enter.native="handleFilter"
                                outlined
                                dense
                            >
                                <template v-slot:label>
                                    {{ filter.label }}
                                    <v-icon dense v-if="filter.name.endsWith('[gte]') || filter.name.endsWith('[after]')">mdi-greater-than-or-equal</v-icon>
                                    <v-icon dense v-if="filter.name.endsWith('[lte]') || filter.name.endsWith('[before]')">mdi-less-than-or-equal</v-icon>
                                </template>
                            </v-text-field>
                        </template>
                        <v-date-picker
                            v-model="filters[[filter.name]]"
                            @input="filter.pickerMenu = false"
                            type="date"
                            no-title
                        />
                    </v-menu>

                    <!-- Boolean Filter -->
                    <v-radio-group
                        v-else-if="filter.type === enumFilterType.Boolean"
                        v-model="filters[[filter.name]]"
                        row
                        prepend-icon="mdi-close-circle"
                        @click:prepend="toggleFilter(filter.name)"
                    >
                        <template v-slot:label>
                            <div>{{ filter.label }} ?</div>
                        </template>
                        <v-radio
                            label="Yes"
                            :value=true
                        />
                        <v-radio
                            label="No"
                            :value=false
                        />
                    </v-radio-group>

                    <!-- Choice Filter -->
                    <v-select
                        v-else-if="filter.type === enumFilterType.Choice"
                        v-model="filters[[filter.name]]"
                        :label="filter.label"
                        :items="filter.choices"
                        item-text="label"
                        item-value="value"
                        multiple
                        small-chips
                        prepend-icon="mdi-close-circle"
                        @click:prepend="toggleFilter(filter.name)"
                        @keyup.enter.native="handleFilter"
                        outlined
                        dense
                    />

                    <!-- String Filter (default) -->
                    <v-text-field
                        v-else
                        v-model="filters[[filter.name]]"
                        :label="filter.label"
                        prepend-icon="mdi-close-circle"
                        @click:prepend="toggleFilter(filter.name)"
                        @keyup.enter.native="handleFilter"
                        outlined
                        dense
                    >
                        <template v-slot:label>
                            {{ filter.label }}
                            <v-icon dense v-if="filter.name.endsWith('[gte]') || filter.name.endsWith('[after]')">mdi-greater-than-or-equal</v-icon>
                            <v-icon dense v-if="filter.name.endsWith('[lte]') || filter.name.endsWith('[before]')">mdi-less-than-or-equal</v-icon>
                        </template>
                    </v-text-field>
                </v-col>
                <v-col class="text-center">
                    <v-btn color="primary" @click="handleFilter">Filter</v-btn>
                    <v-btn color="primary" class="ml-2" text @click="handleReset">Reset</v-btn>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, PropSync } from 'vue-property-decorator'
import { ResourceCollectionFilter } from '../../models/api/ResourceCollection'
import { has, set } from 'lodash'
import { ResourceFilterType } from '../../models/api/ResourceFilterType'

@Component
export default class ListFilter extends Vue {
    @Prop(Function) readonly handleReset!: () => void
    @Prop(Function) readonly handleFilter!: () => void
    @PropSync('initialFilters', Object) filters!: Record<string, undefined | string | number | boolean>
    @PropSync('initialAvailableFilters', Array) availableFilters!: ResourceCollectionFilter[]

    enumFilterType = ResourceFilterType

    get enabledFilters (): ResourceCollectionFilter[] {
        return this.availableFilters.filter(filter => filter.enable)
    }

    get disableFilters (): ResourceCollectionFilter[] {
        return this.availableFilters.filter(filter => !filter.enable)
    }

    toggleFilter (filterName: string): void {
        // Empty value from toggled filter to ensure that no unwanted closed filters remain.
        if (has(this.filters, filterName)) {
            set(this.filters, filterName, undefined)
        }
        this.availableFilters.forEach(function (filter: ResourceCollectionFilter) {
            if (filter.name === filterName) {
                filter.enable = !filter.enable
                filter.value = undefined
            }
        }, { filterName })
        if (this.enabledFilters.length === 0) {
            this.handleReset()
        }
    }
}
</script>
