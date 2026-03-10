import "./bootstrap";
import router from "./router";
import { createApp } from "vue";
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import axios from "axios";

import { aliases, mdi } from 'vuetify/iconsets/mdi'

import App from "./App.vue";

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light'
    },
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        }
    }
});


createApp(App).use(router).use(vuetify).mount("#app");