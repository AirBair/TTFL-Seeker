import { type FilterType } from './enums/FilterType'

export interface Filter {
    key: string
    isActive: boolean
    label: string
    value: string | number | null
    type?: FilterType
    choices?: Array<{ title: string, value: string | number }>
}
