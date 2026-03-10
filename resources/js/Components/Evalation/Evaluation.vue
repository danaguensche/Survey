<!-- Evaluation.vue -->
<template>
    <v-container max-width="960">

        <!-- Ladeindikator -->
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-6" rounded />

        <!-- Fehler -->
        <v-alert v-if="error" type="error" rounded="xl" class="mb-6">
            {{ error }}
        </v-alert>

        <template v-if="!loading && results">

            <!-- Header-Karte -->
            <v-card rounded="xl" elevation="0" border class="mb-6">
                <div
                    class="bg-primary-lighten-5 pa-6 rounded-t-xl d-flex align-center justify-space-between flex-wrap ga-3">
                    <div>
                        <div class="text-h5 font-weight-bold">Auswertung Ausbildungsumfrage</div>
                        <div class="text-body-2 text-grey-darken-1 mt-1">
                            <v-icon size="16" class="mr-1">mdi-account-group</v-icon>
                            {{ results.meta.total_responses }} Antworten gesamt
                        </div>
                    </div>
                    <v-btn color="error" variant="tonal" rounded="xl" prepend-icon="mdi-logout" :loading="loggingOut"
                        @click="logout">
                        Abmelden
                    </v-btn>
                </div>

                <!-- Filter -->
                <v-card-text class="pa-6">
                    <v-row dense>
                        <v-col cols="12" sm="6">
                            <v-select v-model="filterBeruf" :items="berufeOptions" label="Ausbildungsberuf"
                                variant="outlined" density="comfortable" bg-color="surface"
                                prepend-inner-icon="mdi-briefcase-outline" rounded="lg" clearable />
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-select v-model="filterJahr" :items="jahrOptions" label="Ausbildungsjahr"
                                variant="outlined" density="comfortable" bg-color="surface"
                                prepend-inner-icon="mdi-school-outline" rounded="lg" clearable />
                        </v-col>
                    </v-row>

                    <!-- Meta-Chips -->
                    <div class="d-flex flex-wrap ga-2 mt-4">
                        <v-chip v-for="(count, beruf) in results.meta.by_beruf" :key="beruf" size="small"
                            variant="tonal" color="primary">
                            {{ beruf }}: {{ count }}
                        </v-chip>
                        <v-divider vertical class="mx-2" />
                        <v-chip v-for="(count, jahr) in results.meta.by_jahr" :key="'j' + jahr" size="small"
                            variant="tonal" color="secondary">
                            Jahr {{ jahr }}: {{ count }}
                        </v-chip>
                    </div>
                </v-card-text>
            </v-card>

            <!-- Sections -->
            <v-card v-for="(section, sIdx) in ratingSections" :key="sIdx" rounded="xl" elevation="0" class="mb-6">
                <!-- Section-Header -->
                <div
                    class="bg-primary-lighten-5 pa-5 rounded-t-xl d-flex align-center justify-space-between flex-wrap ga-2">
                    <span class="text-subtitle-1 font-weight-bold">{{ section.title }}</span>
                    <v-chip color="primary" size="small" rounded="xl">
                        Ø {{ sectionAverage(section) }} / {{ section.questions[0]?.scale_max ?? 6 }}
                    </v-chip>
                </div>

                <v-card-text class="pa-5">
                    <div v-for="(q, qIdx) in section.questions" :key="q.id"
                        :class="{ 'mb-6': qIdx < section.questions.length - 1 }">
                        <!-- Frage-Header -->
                        <div class="d-flex align-start justify-space-between flex-wrap ga-2 mb-3">
                            <div class="text-body-1 font-weight-medium" style="flex: 1; min-width: 200px">
                                {{ q.question_text }}
                            </div>
                            <div class="d-flex align-center ga-2 flex-shrink-0">
                                <v-chip color="primary" size="small" rounded="xl" variant="tonal">
                                    Ø {{ q.average.toFixed(1) }} / {{ q.scale_max }}
                                </v-chip>
                            </div>
                        </div>

                        <!-- Balkendiagramm -->
                        <div class="distribution-chart">
                            <div v-for="(val, bIdx) in q.distribution" :key="bIdx" class="bar-row">
                                <span class="bar-label text-caption text-grey-darken-1">{{ bIdx + 1 }}</span>
                                <div class="bar-track">
                                    <div class="bar-fill" :style="{ width: barWidth(val, q.distribution) + '%' }"
                                        :class="barColor(bIdx, q.scale_max)" />
                                </div>
                                <span class="bar-value text-caption text-grey-darken-1">{{ val }}</span>
                            </div>
                        </div>

                        <v-divider v-if="qIdx < section.questions.length - 1" class="mt-4" />
                    </div>
                </v-card-text>
            </v-card>

            <!--Freitextantworten-->
            <v-card v-if="results.text_answers && results.text_answers.length" rounded="xl" elevation="0" border
                class="mb-6">
                <div class="bg-primary-lighten-5 pa-5 rounded-t-xl">
                    <span class="text-subtitle-1 font-weight-bold">
                        <v-icon class="mr-2">mdi-comment-text-multiple-outline</v-icon>
                        Freitextantworten
                    </span>
                </div>

                <v-card-text class="pa-5">
                    <div v-for="(block, bIdx) in results.text_answers" :key="bIdx"
                        :class="{ 'mb-6': bIdx < results.text_answers.length - 1 }">
                        <div class="text-body-1 font-weight-medium mb-3">{{ block.question_text }}</div>
                        <v-list density="compact" rounded="xl" bg-color="grey-lighten-4" class="pa-0">

                            <v-list-item v-for="(answer, aIdx) in block.answers" :key="aIdx"
                                :class="{ 'border-b': aIdx < block.answers.length - 1 }">
                                <template #prepend>
                                    <v-icon size="16" color="primary" class="mr-2">mdi-chevron-right</v-icon>
                                </template>
                                <v-list-item-title class="text-body-2 text-wrap">
                                    {{ answer.text }}
                                </v-list-item-title>
                                <template #append>
                                    <div class="d-flex ga-1 ml-3 flex-shrink-0">
                                        <v-chip v-if="answer.beruf" size="x-small" color="primary" variant="tonal"
                                            rounded="xl">
                                            {{ answer.beruf }}
                                        </v-chip>
                                        <v-chip v-if="answer.jahr" size="x-small" color="secondary" variant="tonal"
                                            rounded="xl">
                                            Jahr {{ answer.jahr }}
                                        </v-chip>
                                    </div>
                                </template>
                            </v-list-item>

                        </v-list>
                        <v-divider v-if="bIdx < results.text_answers.length - 1" class="mt-4" />
                    </div>
                </v-card-text>
            </v-card>

        </template>
    </v-container>
</template>

<script>
import axios from 'axios'

export default {
    name: 'Evaluation',

    data() {
        return {
            loading: false,
            loggingOut: false,
            error: null,
            results: null,
            filterBeruf: null,
            filterJahr: null,
        }
    },

    computed: {
        berufeOptions() {
            if (!this.results) return []
            return Object.keys(this.results.meta.by_beruf)
        },
        jahrOptions() {
            if (!this.results) return []
            return Object.keys(this.results.meta.by_jahr).map(j => ({
                title: `Jahr ${j}`,
                value: j,
            }))
        },
        ratingSections() {
            if (!this.results) return []
            return this.results.sections.filter(s => s.title !== 'Hinweise und Verbesserungen')
        }
    },

    watch: {
        filterBeruf() {
            this.fetchResults()
        },
        filterJahr() {
            this.fetchResults()
        },
    },

    mounted() {
        this.fetchResults()
    },

    methods: {
        async fetchResults() {
            this.loading = true
            this.error = null
            try {
                const params = {}
                if (this.filterBeruf) params.beruf = this.filterBeruf
                if (this.filterJahr) params.jahr = this.filterJahr

                const { data } = await axios.get('/api/survey/results', {
                    withCredentials: true,
                    params,
                })
                this.results = data
            } catch (err) {
                if (err.response?.status === 401 || err.response?.status === 403) {
                    this.$router.push('/')
                } else {
                    this.error = 'Fehler beim Laden der Auswertung. Bitte versuche es erneut.'
                }
            } finally {
                this.loading = false
            }
        },

        async logout() {
            this.loggingOut = true
            try {
                await axios.post('/api/logout', {}, { withCredentials: true })
            } catch {
            } finally {
                this.loggingOut = false
                this.$router.push('/')
            }
        },

        sectionAverage(section) {
            if (!section.questions.length) return '–'
            const avg =
                section.questions.reduce((sum, q) => sum + q.average, 0) /
                section.questions.length
            return avg.toFixed(1)
        },

        barWidth(val, distribution) {
            const max = Math.max(...distribution)
            if (max === 0) return 0
            return Math.round((val / max) * 100)
        },

        // Farb-Gradient: niedrig = rot, mittel = gelb, hoch = grün
        barColor(index, scaleMax) {
            const ratio = index / (scaleMax - 1)
            if (ratio < 0.34) return 'bar-low'
            if (ratio < 0.67) return 'bar-mid'
            return 'bar-high'
        },
    },
}
</script>

<style scoped>
.distribution-chart {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.bar-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.bar-label {
    width: 14px;
    text-align: right;
    flex-shrink: 0;
}

.bar-track {
    flex: 1;
    background: #f0f0f0;
    border-radius: 99px;
    height: 12px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 0.4s ease;
    min-width: 4px;
}

.bar-value {
    width: 24px;
    flex-shrink: 0;
}

/* Farbklassen */
.bar-low {
    background: #ef9a9a;
}

.bar-mid {
    background: #fff176;
}

.bar-high {
    background: #a5d6a7;
}
</style>