<template>
    <v-container max-width="900">
        <!-- Fortschrittsbalken -->
        <v-progress-linear :model-value="progress" color="primary" class="mb-6" rounded />

        <v-card class="pa-6 mb-6">
            <v-card-title class="text-center text-h5 font-weight-bold">
                Zufriedenheitsumfrage für Auszubildende
            </v-card-title>
            <p class="text-center text-grey mb-4">
                Bewertung der Maßnahme im aktuellen Ausbildungsjahr
            </p>

            <v-form ref="form" @submit.prevent="submitForm">

                <!-- Angaben zur Person -->
                <v-card color="blue-lighten-5" class="pa-4 mb-6" variant="outlined">
                    <v-card-subtitle class="text-h6 text-blue-darken-3 mb-4">
                        Optional: Angaben zur Person
                    </v-card-subtitle>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select v-model="formData.ausbildungsberuf" :items="berufe" label="Ausbildungsberuf"
                                variant="outlined" density="compact" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select v-model="formData.ausbildungsjahr" :items="['1', '2', '3', '4']"
                                label="Ausbildungsjahr" variant="outlined" density="compact" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.datum" type="date" label="Datum" variant="outlined"
                                density="compact" />
                        </v-col>
                        <v-col cols="12" md="6" class="d-flex align-center">
                            <v-checkbox v-model="formData.consent"
                                label="Ich stimme der Verarbeitung meiner Daten zu" />
                        </v-col>
                    </v-row>
                </v-card>

                <!-- Sektion 1: Rahmenbedingungen -->
                <v-card variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-4">
                        1. Rahmenbedingungen und Ausstattung
                    </v-card-subtitle>

                    <rating-scale v-for="(frage, index) in sektion1Fragen" :key="index" :label="frage.label"
                        v-model="frage.value" class="mb-4" />
                </v-card>

                <!-- Frage 2 -->
                <v-card variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-2">
                        2. Wie bewerten Sie die fachliche Kompetenz der Dozenten?
                    </v-card-subtitle>
                    <rating-scale v-model="formData.q2" low-label="Ungenügend" high-label="Sehr gut" />
                </v-card>

                <!-- Frage 3 -->
                <v-card variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-2">
                        3. Wie beurteilen Sie die Organisation der Maßnahme?
                    </v-card-subtitle>
                    <rating-scale v-model="formData.q3" low-label="Schlecht" high-label="Ausgezeichnet" />
                </v-card>

                <!-- Frage 4 -->
                <v-card variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-2">
                        4. Was hat Ihnen besonders gut gefallen?
                    </v-card-subtitle>
                    <v-textarea v-model="formData.q4" placeholder="Ihre Antwort..." variant="outlined" rows="3" />
                </v-card>

                <!-- Frage 5 -->
                <v-card variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-2">
                        5. Was könnte verbessert werden?
                    </v-card-subtitle>
                    <v-textarea v-model="formData.q5" placeholder="Ihre Anregungen..." variant="outlined" rows="3" />
                </v-card>

                <!-- Buttons -->
                <div class="d-flex justify-space-between gap-4">
                    <v-btn variant="tonal" prepend-icon="mdi-content-save" @click="saveDraft">
                        Zwischenspeichern
                    </v-btn>
                    <v-btn type="submit" color="primary" prepend-icon="mdi-send">
                        Umfrage absenden
                    </v-btn>
                </div>

            </v-form>
        </v-card>
    </v-container>
</template>

<script>
import RatingScale from './RatingScale.vue'

export default {
    name: 'SurveyForm',

    components: { RatingScale },

    data() {
        return {
            berufe: ['Metall', 'Büro', 'IT', 'Lager'],

            formData: {
                ausbildungsberuf: '',
                ausbildungsjahr: '',
                datum: '',
                consent: false,
                q2: null,
                q3: null,
                q4: '',
                q5: ''
            },

            sektion1Fragen: [
                { label: 'Die genutzten Räume und Werkstätten waren in ordnungsgemäßem Zustand.', value: null },
                { label: 'Die technische Ausstattung und Ausbildungsmittel standen mir ausreichend zur Verfügung.', value: null },
                { label: 'Ich bekam regelmäßige Rückmeldungen zu meinem Lern- und Leistungsstand.', value: null }
            ]
        }
    },

    computed: {
        progress() {
            const fields = [
                this.formData.ausbildungsberuf,
                this.formData.ausbildungsjahr,
                this.formData.datum,
                ...this.sektion1Fragen.map(f => f.value),
                this.formData.q2,
                this.formData.q3,
                this.formData.q4,
                this.formData.q5
            ]
            const filled = fields.filter(f => f !== null && f !== '').length
            return Math.round((filled / fields.length) * 100)
        }
    },

    methods: {
        submitForm() {
            alert('Vielen Dank für Ihre Teilnahme!')
            this.$refs.form.reset()
            this.sektion1Fragen.forEach(f => f.value = null)
        },

        saveDraft() {
            localStorage.setItem('surveyDraft', JSON.stringify({
                formData: this.formData,
                sektion1Fragen: this.sektion1Fragen
            }))
            alert('Zwischengespeichert!')
        }
    },

    mounted() {
        const draft = localStorage.getItem('surveyDraft')
        if (draft) {
            const parsed = JSON.parse(draft)
            this.formData = parsed.formData
            this.sektion1Fragen = parsed.sektion1Fragen
        }
    }
}
</script>