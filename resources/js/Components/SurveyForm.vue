<template>
    <v-container max-width="900">
        <v-progress-linear :model-value="progress" color="primary" class="mb-8" rounded height="6" />

        <v-card class="pa-8 mb-6" rounded="xl" elevation="0" border>
            <div class="text-center mb-8">
                <h1 class="text-h4 font-weight-bold text-high-emphasis mb-2">Zufriedenheitsumfrage für Auszubildende</h1>
                <p class="text-body-1 text-medium-emphasis">Bewertung der Maßnahme im aktuellen Ausbildungsjahr</p>
            </div>

            <v-form ref="form" @submit.prevent="submitForm">

                <!-- Angaben zur Person -->
                <v-sheet class="pa-6 mb-8" variant="tonal" color="blue-lighten-5" rounded="lg">
                    <p class="text-subtitle-1 font-weight-semibold text-primary mb-4">Optional: Angaben zur Person</p>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select v-model="formData.ausbildungsberuf" :items="berufe" label="Ausbildungsberuf"
                                variant="outlined" density="comfortable" bg-color="surface"
                                prepend-inner-icon="mdi-briefcase-outline" rounded="lg" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select v-model="formData.ausbildungsjahr" :items="['1','2','3','4']" label="Ausbildungsjahr"
                                variant="outlined" density="comfortable" bg-color="surface"
                                prepend-inner-icon="mdi-school-outline" rounded="lg" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field v-model="formData.datum" type="date" label="Datum"
                                variant="outlined" density="comfortable" bg-color="surface"
                                 rounded="lg" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-checkbox v-model="formData.consent" color="blue-darken-2">
                                <template #label>
                                    <span class="text-body-2">Ich stimme der Verarbeitung meiner Daten zu</span>
                                </template>
                            </v-checkbox>
                        </v-col>
                    </v-row>
                </v-sheet>

                <!-- Bewertungsfragen -->
                <v-card v-for="section in ratingQuestions" :key="section.title" class="mb-6" rounded="xl" border elevation="0">
                    <v-card-item class="bg-primary-lighten-5 pa-5">
                        <v-card-title class="text-subtitle-1 font-weight-bold">{{ section.title }}</v-card-title>
                    </v-card-item>
                    <v-divider />
                    <v-card-text class="pa-5">
                        <div v-for="(q, idx) in section.questions" :key="q.id">
                            <rating-scale :label="q.label" :low-label="q.lowLabel" :high-label="q.highLabel"
                                v-model="answers[q.id].rating_value" />
                            <v-divider v-if="idx < section.questions.length - 1" class="my-4" />
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Freitextfragen -->
                <v-card v-for="q in textQuestions" :key="q.id" class="mb-6" rounded="xl" border elevation="0">
                    <v-card-item class="bg-primary-lighten-5 pa-5">
                        <v-card-title class="text-subtitle-1 font-weight-bold">{{ q.label }}</v-card-title>
                    </v-card-item>
                    <v-divider />
                    <v-card-text class="pa-5">
                        <v-textarea v-model="answers[q.id].text_value" :placeholder="q.placeholder"
                            variant="outlined" rows="3" auto-grow hide-details rounded="lg" bg-color="surface" />
                    </v-card-text>
                </v-card>

                <!-- Buttons -->
                <div class="d-flex justify-space-between align-center flex-wrap ga-3">
                    <v-btn variant="tonal" color="secondary" prepend-icon="mdi-content-save-outline"
                        size="large" rounded="lg" @click="saveDraft">
                        Zwischenspeichern
                    </v-btn>
                    <v-btn type="submit" color="primary" append-icon="mdi-send" size="large" rounded="lg" elevation="2">
                        Umfrage absenden
                    </v-btn>
                </div>

            </v-form>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios'
import RatingScale from './RatingScale.vue'

const RATING_IDS = [1, 2, 3, 4, 5]
const TEXT_IDS = [6, 7]

export default {
    name: 'SurveyForm',
    components: { RatingScale },

    data: () => ({
        berufe: ['Metall', 'Büro', 'IT', 'Lager'],

        ratingQuestions: [
            {
                title: '1. Rahmenbedingungen und Ausstattung',
                questions: [
                    { id: 1, label: 'Die genutzten Räume und Werkstätten waren in ordnungsgemäßem Zustand.' },
                    { id: 2, label: 'Die technische Ausstattung und Ausbildungsmittel standen mir ausreichend zur Verfügung.' },
                    { id: 3, label: 'Ich bekam regelmäßige Rückmeldungen zu meinem Lern- und Leistungsstand.' },
                ]
            },
            {
                title: '2. Wie bewerten Sie die fachliche Kompetenz der Dozenten?',
                questions: [{ id: 4, lowLabel: 'Sehr unzufrieden', highLabel: 'Sehr zufrieden' }]
            },
            {
                title: '2. Wie bewerten Sie die fachliche Kompetenz der Dozenten?',
                questions: [{ id: 4, lowLabel: 'Sehr unzufrieden', highLabel: 'Sehr zufrieden' }]
            },
        ],

        textQuestions: [
            { id: 6, label: '4. Was hat Ihnen besonders gut gefallen?', placeholder: 'Ihre Antwort...' },
            { id: 7, label: '5. Was könnte verbessert werden?', placeholder: 'Ihre Anregungen...' },
        ],

        formData: { ausbildungsberuf: '', ausbildungsjahr: '', datum: '', consent: false },

        answers: {
            ...Object.fromEntries(RATING_IDS.map(id => [id, { question_id: id, rating_value: null, text_value: null }])),
            ...Object.fromEntries(TEXT_IDS.map(id => [id, { question_id: id, rating_value: null, text_value: '' }])),
        },
    }),

    computed: {
        progress() {
            const values = [
                this.formData.ausbildungsberuf,
                this.formData.ausbildungsjahr,
                this.formData.datum,
                ...Object.values(this.answers).map(a => a.rating_value ?? a.text_value),
            ]
            return Math.round(values.filter(v => v !== null && v !== '').length / values.length * 100)
        },
    },

    methods: {
        submitForm() {
            const payload = {
                ...this.formData,
                ausbildungsberuf: this.formData.ausbildungsberuf || null,
                ausbildungsjahr: this.formData.ausbildungsjahr || null,
                datum: this.formData.datum || null,
                answers: Object.values(this.answers),
            }
            axios.post('/api/survey', payload)
                .then(() => { alert('Vielen Dank für Ihre Teilnahme!'); this.resetForm() })
                .catch(err => { console.error(err.response?.data); alert('Fehler beim Absenden der Umfrage.') })
        },

        saveDraft() {
            localStorage.setItem('surveyDraft', JSON.stringify({ formData: this.formData, answers: this.answers }))
            alert('Zwischengespeichert!')
        },

        resetForm() {
            this.$refs.form.reset()
            this.formData = { ausbildungsberuf: '', ausbildungsjahr: '', datum: '', consent: false }
            this.answers = {
                ...Object.fromEntries(RATING_IDS.map(id => [id, { question_id: id, rating_value: null, text_value: null }])),
                ...Object.fromEntries(TEXT_IDS.map(id => [id, { question_id: id, rating_value: null, text_value: '' }])),
            }
        },
    },

    mounted() {
        try {
            const draft = JSON.parse(localStorage.getItem('surveyDraft'))
            if (draft) {
                this.formData = draft.formData ?? this.formData
                this.answers = draft.answers ?? this.answers
            }
        } catch {
            localStorage.removeItem('surveyDraft')
        }
    },
}
</script>