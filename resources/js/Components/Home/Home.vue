<template>
    <v-container max-width="700" class="fill-height d-flex align-center justify-center">
        <v-col>
            <div class="text-center mb-10">
                <v-icon size="64" color="primary" class="mb-4">mdi-clipboard-text-outline</v-icon>
                <h1 class="text-h4 font-weight-bold mb-2">Ausbildungsbewertung</h1>
                <p class="text-body-1 text-medium-emphasis">Berufsbildungswerk – Jährliche Umfrage</p>
            </div>

            <v-row justify="center" class="ga-4">
                <v-col cols="12" md="5">
                    <v-card rounded="xl" border elevation="0" class="pa-6 text-center h-100">
                        <v-icon size="40" color="primary" class="mb-4">mdi-pencil-outline</v-icon>
                        <h2 class="text-h6 font-weight-bold mb-2">Zur Umfrage</h2>
                        <p class="text-body-2 text-medium-emphasis mb-6">
                            Nehmen Sie an der aktuellen Jahresumfrage teil und geben Sie Ihr Feedback.
                        </p>
                        <v-btn color="primary" rounded="lg" size="large" block @click="$router.push('/survey')">
                            Umfrage starten
                        </v-btn>
                    </v-card>
                </v-col>

                <v-col cols="12" md="5">
                    <v-card rounded="xl" border elevation="0" class="pa-6 text-center h-100">
                        <v-icon size="40" color="secondary" class="mb-4">mdi-chart-bar</v-icon>
                        <h2 class="text-h6 font-weight-bold mb-2">Auswertung</h2>
                        <p class="text-body-2 text-medium-emphasis mb-6">
                            Ergebnisse und Statistiken der eingegangenen Antworten einsehen.
                        </p>
                        <v-btn color="secondary" rounded="lg" size="large" block variant="tonal" @click="openLogin">
                            Anmelden
                        </v-btn>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>

        <!-- Login Dialog -->
        <v-dialog v-model="loginDialog" max-width="400">
            <v-card rounded="xl" class="pa-6">
                <h2 class="text-h6 font-weight-bold mb-6 text-center">Anmelden</h2>
                <v-form @submit.prevent="submitLogin">
                    <v-text-field v-model="loginForm.username" label="Benutzername" variant="outlined"
                        prepend-inner-icon="mdi-account-outline" rounded="lg" class="mb-3" />
                    <v-text-field v-model="loginForm.password" label="Passwort" type="password" variant="outlined"
                        prepend-inner-icon="mdi-lock-outline" rounded="lg" class="mb-2" />
                    <p v-if="loginError" class="text-caption text-error mb-3">{{ loginError }}</p>
                    <v-btn type="submit" color="primary" rounded="lg" size="large" block :loading="loginLoading">
                        Anmelden
                    </v-btn>
                </v-form>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from 'axios'
import { ref } from 'vue'

export default {
    name: 'Home',

    data: () => ({
        loginDialog: false,
        loginLoading: false,
        loginError: '',
        loginForm: { username: '', password: '' },
    }),

    methods: {
        openLogin() {
            this.loginError = ''
            this.loginForm = { username: '', password: '' }
            this.loginDialog = true
        },

        async submitLogin() {
            this.loginLoading = true
            this.loginError = ''
            try {
                await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
                await axios.post('/api/login', this.loginForm, { withCredentials: true })
                this.loginDialog = false
                this.$router.push('/evaluation')
            } catch {
                this.loginError = 'E-Mail oder Passwort falsch.'
            } finally {
                this.loginLoading = false
            }
        },
    },
}
</script>