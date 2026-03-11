<template>
    <v-container max-width="900">
        <div
            style="position: sticky; top: 0; z-index: 100; background: rgb(var(--v-theme-background)); padding: 8px 0;">
            <v-progress-linear :model-value="progress" color="primary" rounded height="6" />
        </div>

        <v-card class="pa-8 mb-6" rounded="xl" elevation="0" border>
            <div class="text-center mb-8">
                <h2 class="font-weight-bold mb-2">Ihre Meinung ist uns wichtig!</h2>
                <p class="text-body-1 text-medium-emphasis">Bitte nehmen Sie sich 5 Minuten Zeit, um diese anonyme
                    Umfrage auszufüllen.</p>
            </div>

            <v-form ref="form" @submit.prevent="submitForm">

                <v-sheet class="pa-6 mb-8" variant="tonal" color="blue-lighten-5" rounded="lg">
                    <p class="text-subtitle-1 font-weight-semibold text-primary mb-4">Optional: Angaben zur Person</p>
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-select v-model="formData.ausbildungsberuf" :items="berufe" label="Ausbildungsberuf"
                                variant="outlined" density="comfortable" bg-color="surface"
                                prepend-inner-icon="mdi-briefcase-outline" rounded="lg"
                                :rules="[v => !!v || 'Bitte wählen Sie einen Ausbildungsberuf aus']" />
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-checkbox v-model="formData.consent" color="blue-darken-2"
                                :rules="[v => !!v || 'Bitte stimmen Sie der Datenverarbeitung zu']">
                                <template #label>
                                    <span class="text-body-2">Ich stimme der Verarbeitung meiner Daten zu</span>
                                </template>
                            </v-checkbox>
                        </v-col>
                    </v-row>
                </v-sheet>

                <template v-for="section in sections" :key="section.title">
                    <v-card class="mb-6" rounded="xl" border elevation="0">
                        <v-card-item class="bg-primary-lighten-5 pa-5">
                            <v-card-title class="text-title-medium font-weight-bold">{{ section.title }}</v-card-title>
                        </v-card-item>
                        <v-divider />
                        <v-card-text class="pa-5">
                            <template v-for="(q, idx) in section.questions" :key="q.id">

                                <!-- Rating -->
                                <div v-if="q.type === 'rating'">
                                    <rating-scale :label="q.question_text" low-label="Trifft nicht zu"
                                        high-label="Trifft voll zu" :scale-max="q.scale_max" v-model="answers[q.id]" />
                                </div>

                                <!-- Text -->
                                <div v-else-if="q.type === 'text'">
                                    <p class="text-body-2 mb-2">{{ q.question_text }}</p>
                                    <v-textarea v-model="answers[q.id]" placeholder="Ihre Antwort..." variant="outlined"
                                        rows="3" auto-grow hide-details rounded="lg" bg-color="surface" />
                                </div>

                                <v-divider v-if="idx < section.questions.length - 1" class="my-4" />
                            </template>
                        </v-card-text>
                    </v-card>
                </template>

                <div class="d-flex justify-space-between align-center flex-wrap ga-3">
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

export default {
    name: 'SurveyForm',
    components: { RatingScale },

    data: () => ({
        berufe: [],
        sections: [],
        answers: {},
        formData: { ausbildungsberuf: '', ausbildungsjahr: '', datum: '', consent: false },
    }),

    computed: {
        allQuestions() {
            return this.sections.flatMap(s => s.questions)
        },
        progress() {
            if (!this.allQuestions.length) return 0

            const formFields = [
                this.formData.ausbildungsberuf,
                this.formData.ausbildungsjahr,
                this.formData.datum,
            ]

            const filledForm = formFields.filter(v => v !== null && v !== '').length
            const filledAnswers = this.allQuestions.filter(q => {
                const v = this.answers[q.id]
                return v !== null && v !== '' && v !== undefined
            }).length

            const total = formFields.length + this.allQuestions.length
            return Math.round((filledForm + filledAnswers) / total * 100)
        },
    },

    methods: {

        async getApprenticeships() {
            try {
                const { data } = await axios.get('/api/apprenticeships')
                this.berufe = data.map(a => a.title)
            } catch (err) {
                console.error('Fehler beim Laden der Ausbildungsberufe:', err)
            }
        },

        async loadQuestions() {
            try {
                const { data } = await axios.get('/api/survey/sections')

                // Fragen nach section gruppieren
                const sectionMap = {}
                data.forEach(q => {
                    if (!sectionMap[q.section]) sectionMap[q.section] = { title: q.section, questions: [] }
                    sectionMap[q.section].questions.push(q)
                })
                this.sections = Object.values(sectionMap)

                // answers initialisieren
                this.answers = Object.fromEntries(data.map(q => [q.id, q.type === 'text' ? '' : null]))
            } catch (err) {
                console.error('Fehler beim Laden der Fragen:', err)
            }
        },

        async submitForm() {
            const payload = {
                ...this.formData,
                answers: this.allQuestions.map(q => ({
                    question_id: q.id,
                    rating_value: q.type === 'rating' ? this.answers[q.id] : null,
                    text_value: q.type === 'text' ? this.answers[q.id] : null,
                })),
            }
            try {
                await axios.post('/api/survey', payload)
                alert('Vielen Dank für Ihre Teilnahme!')
                this.resetForm()
            } catch (err) {
                console.error(err.response?.data)
                alert('Fehler beim Absenden der Umfrage.')
            }
        },

        resetForm() {
            this.formData = { ausbildungsberuf: '', ausbildungsjahr: '', datum: '', consent: false }
            this.answers = Object.fromEntries(this.allQuestions.map(q => [q.id, q.type === 'text' ? '' : null]))
        },

    },

    async mounted() {
        await this.loadQuestions()
        await this.getApprenticeships()
        try {
            const draft = JSON.parse(localStorage.getItem('surveyDraft'))
            if (draft) {
                // Nur laden wenn die IDs noch übereinstimmen
                const draftIds = Object.keys(draft.answers ?? {}).map(Number).sort().join()
                const currentIds = this.allQuestions.map(q => q.id).sort().join()
                if (draftIds === currentIds) {
                    this.formData = draft.formData ?? this.formData
                    this.answers = draft.answers ?? this.answers
                } else {
                    localStorage.removeItem('surveyDraft')
                }
            }
        } catch {
            localStorage.removeItem('surveyDraft')
        }
    },
}
</script>