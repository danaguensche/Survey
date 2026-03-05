<template>
    <v-container max-width="900">
        <v-progress-linear :model-value="progress" color="primary" class="mb-6" rounded />

        <v-card class="pa-6 mb-6">
            <v-card-title class="text-center text-h5 font-weight-bold">
                Zufriedenheitsumfrage für Auszubildende
            </v-card-title>
            <p class="text-center text-grey mb-4">Bewertung der Maßnahme im aktuellen Ausbildungsjahr</p>

            <v-form ref="form" @submit.prevent="submitForm">

                <!-- Angaben zur Person -->
                <v-card color="blue-lighten-5" class="pa-4 mb-6" variant="outlined">
                    <v-card-subtitle class="text-h6 text-blue-darken-3 mb-4">Optional: Angaben zur
                        Person</v-card-subtitle>
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

                <!-- Alle Bewertungsfragen -->
                <v-card v-for="section in ratingQuestions" :key="section.title" variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-4">{{ section.title }}</v-card-subtitle>
                    <rating-scale v-for="q in section.questions" :key="q.id" :label="q.label" :low-label="q.lowLabel"
                        :high-label="q.highLabel" v-model="answers[q.id].rating_value" class="mb-4" />
                </v-card>

                <!-- Freitextfragen -->
                <v-card v-for="q in textQuestions" :key="q.id" variant="outlined" class="pa-4 mb-6">
                    <v-card-subtitle class="text-h6 mb-2">{{ q.label }}</v-card-subtitle>
                    <v-textarea v-model="answers[q.id].text_value" :placeholder="q.placeholder" variant="outlined"
                        rows="3" />
                </v-card>

                <!-- Buttons -->
                <div class="d-flex justify-space-between gap-4">
                    <v-btn variant="tonal" prepend-icon="mdi-content-save" @click="saveDraft">Zwischenspeichern</v-btn>
                    <v-btn type="submit" color="primary" prepend-icon="mdi-send">Umfrage absenden</v-btn>
                </div>

            </v-form>
        </v-card>
    </v-container>
</template>


<script>
import axios from 'axios'
import RatingScale from './RatingScale.vue'

const RATING_QUESTIONS = [
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
        questions: [{ id: 4, lowLabel: 'Ungenügend', highLabel: 'Sehr gut' }]
    },
    {
        title: '3. Wie beurteilen Sie die Organisation der Maßnahme?',
        questions: [{ id: 5, lowLabel: 'Schlecht', highLabel: 'Ausgezeichnet' }]
    },
]

const TEXT_QUESTIONS = [
    { id: 6, label: '4. Was hat Ihnen besonders gut gefallen?', placeholder: 'Ihre Antwort...' },
    { id: 7, label: '5. Was könnte verbessert werden?', placeholder: 'Ihre Anregungen...' },
]

const buildAnswers = () => Object.fromEntries([
    ...[1, 2, 3, 4, 5].map(id => [id, { question_id: id, rating_value: null, text_value: null }]),
    ...[6, 7].map(id => [id, { question_id: id, rating_value: null, text_value: '' }]),
])

const FORM_DATA_DEFAULT = () => ({ ausbildungsberuf: '', ausbildungsjahr: '', datum: '', consent: false })

export default {
    name: 'SurveyForm',
    components: { RatingScale },

    data: () => ({
        berufe: ['Metall', 'Büro', 'IT', 'Lager'],
        ratingQuestions: RATING_QUESTIONS,
        textQuestions: TEXT_QUESTIONS,
        formData: FORM_DATA_DEFAULT(),
        answers: buildAnswers(),
    }),

    computed: {
        progress() {
            const values = [
                this.formData.ausbildungsberuf,
                this.formData.ausbildungsjahr,
                this.formData.datum,
                ...Object.values(this.answers).map(a => a.rating_value ?? a.text_value),
            ]
            const filled = values.filter(v => v !== null && v !== '').length
            return Math.round((filled / values.length) * 100)
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
            this.formData = FORM_DATA_DEFAULT()
            this.answers = buildAnswers()
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