import "./bootstrap";
import router from "./router";
import { createApp } from "vue";

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import App from "./App.vue";

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light'
    }
})


createApp(App).use(router).use(vuetify).mount("#app");