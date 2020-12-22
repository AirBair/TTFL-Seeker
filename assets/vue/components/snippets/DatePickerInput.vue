<template>
    <v-menu
        v-model="isOpen"
        transition="scale-transition"
        offset-y
        min-width="290px"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                v-model="dateFormatted"
                :label="label"
                append-icon="mdi-calendar"
                :dense="dense"
                :required="required"
                :outlined="outlined"
                :clearable="clearable"
                @blur="isoDate = parseDate(dateFormatted)"
                v-on="on"
                @click:append="isOpen = !isOpen"
                :rules="rules"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="isoDate"
            @input="isOpen = false"
            :type="type"
            :first-day-of-week="1"
        />
    </v-menu>
</template>

<script lang="ts">
import Vue from 'vue'
import { Component, Prop, Watch } from 'vue-property-decorator'

@Component
export default class DatePickerInput extends Vue {
    @Prop() readonly label!: string
    @Prop() readonly date!: string | undefined
    @Prop({ default: false }) readonly required!: boolean
    @Prop({ default: false }) readonly dense!: boolean
    @Prop({ default: false }) readonly outlined!: boolean
    @Prop({ default: false }) readonly clearable!: boolean
    @Prop({ default: () => [] }) readonly rules!: []
    @Prop({ default: 'date' }) readonly type!: string

    dateFormatted: string | null = null
    isOpen = false

    get isoDate (): string | undefined {
        return this.date?.substr(0, 10)
    }

    set isoDate (date: string | undefined) {
        this.$emit('update:date', date)
    }

    @Watch('date')
    onDateChange (): void {
        this.dateFormatted = this.formatDate(this.isoDate)
    }

    created (): void {
        this.onDateChange()
    }

    formatDate (date: string | undefined): string | null {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
    }

    parseDate (date: string | null): string | undefined {
        if (!date) return undefined

        const [day, month, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    }
}
</script>
