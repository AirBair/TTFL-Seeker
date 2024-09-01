import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'

export default createVuetify({
    defaults: {
        VAutocomplete: {
            variant: 'outlined',
            density: 'compact',
            hideDetails: 'auto',
            clearable: true,
        },
        VDataTableServer: {
            itemsPerPageOptions: [10, 30, 50, 100],
        },
        VRadioGroup: {
            density: 'compact',
            hideDetails: 'auto',
        },
        VSelect: {
            variant: 'outlined',
            density: 'compact',
            hideDetails: 'auto',
            clearable: true,
        },
        VTextField: {
            variant: 'outlined',
            density: 'compact',
            hideDetails: 'auto',
        },
    },
})
