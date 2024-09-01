import eslintPluginVue from 'eslint-plugin-vue'
import vueEslintParser from 'vue-eslint-parser'
import typescriptEslint from 'typescript-eslint'
import eslintPluginPromise from 'eslint-plugin-promise'
import stylistic from '@stylistic/eslint-plugin'

export default [
    {
        // https://eslint.org/docs/latest/use/configure/configuration-files#globally-ignoring-files-with-ignores
        ignores: [
            // "**/node_modules/" & ".git/" are already ignored
            'eslint.config.js',
            'webpack.config.js',
        ]
    },

    // Stylistic
    stylistic.configs.customize({
        indent: 4,
        jsx: false,
    }),

    // Typescript
    ...typescriptEslint.configs.strictTypeChecked,

    // Promise
    eslintPluginPromise.configs['flat/recommended'],

    // Vue
    ...eslintPluginVue.configs['flat/recommended'],
    {
        languageOptions: {
            parser: vueEslintParser,
            parserOptions: {
                parser: typescriptEslint.parser,
                project: './tsconfig.json',
                extraFileExtensions: ['.vue'],
            },
        },
    },
    {
        rules: {
            'vue/script-indent': ['warn', 4, { "switchCase": 1 }],
            'vue/html-indent': ['warn', 4],
            'vue/component-name-in-template-casing': ["error", "PascalCase", {
                registeredComponentsOnly: true
            }]
        },
    },
];
