module.exports = {
    root: true,
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        project: './tsconfig.json',
        extraFileExtensions: ['.vue'],
    },
    extends: [
        'love',
        'plugin:vue/vue3-recommended',
    ],
    plugins: [
        '@typescript-eslint'
    ],
    rules: {
        'indent': ['warn', 4, { "SwitchCase": 1 }],
        '@typescript-eslint/indent': ['warn', 4],
        'vue/script-indent': ['warn', 4, { "switchCase": 1 }],
        'vue/html-indent': ['warn', 4],
        'vue/component-name-in-template-casing': ["error", "PascalCase", {
            registeredComponentsOnly: true
        }]
    }
}
