<template>
  <v-container max-width="960">
    <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-6" rounded />

    <v-alert v-if="error" type="error" rounded="xl" class="mb-6">
      {{ error }}
    </v-alert>

    <!-- Header-Karte -->
    <v-card v-if="results" rounded="xl" elevation="0" border class="mb-6">
      <div class="bg-primary-lighten-5 pa-6 rounded-t-xl d-flex align-center justify-space-between flex-wrap ga-3">
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
          <v-col cols="12" sm="12">
            <v-select v-model="filterBeruf" :items="berufeOptions" label="Ausbildungsberuf" variant="outlined"
              density="comfortable" bg-color="surface" prepend-inner-icon="mdi-briefcase-outline" rounded="lg" clearable
              @update:model-value="fetchResults" />
          </v-col>
        </v-row>

        <!-- Meta-Chips -->
        <div class="d-flex flex-wrap ga-2 mt-4">
          <v-chip v-for="(count, beruf) in results.meta.by_beruf" :key="beruf" size="small" variant="tonal"
            color="primary">
            {{ beruf }}: {{ count }}
          </v-chip>
        </div>
      </v-card-text>
    </v-card>

    <!-- Freitextantworten -->
    <v-card v-if="!loading && results?.text_answers?.length" rounded="xl" elevation="0" border class="mb-6">
      <v-card-text class="pa-5">
        <div v-for="(block, bIdx) in results.text_answers" :key="bIdx">

          <div class="text-title-large pa-5 font-weight-medium mb-4">
            {{ block.question_text }}
            <v-chip v-if="block.answers.length" color="primary" size="small" class="ml-3" rounded="xl">
              {{ block.answers.length }} Antwort{{ block.answers.length !== 1 ? 'en' : '' }}
            </v-chip>
          </div>

          <v-list density="compact" rounded="xl" bg-color="grey-lighten-5" class="pa-5">
            <v-list-item v-for="(answer, aIdx) in block.answers" :key="aIdx" rounded="lg" class="ma-1 mb-5">

              <v-list-item-title class="text-body-1 text-wrap line-height-1-5 mb-5">
                {{ answer.text }}
              </v-list-item-title>
                <v-chip v-if="answer.beruf" size="small" color="primary" variant="tonal" rounded="xl" class="mb-5">
                  {{ answer.beruf }}
                </v-chip>
            </v-list-item>
          </v-list>

          <v-divider v-if="bIdx < results.text_answers.length - 1" class="mt-6" />
        </div>
      </v-card-text>
    </v-card>

    <!-- Keine Antworten -->
    <v-card v-if="!loading && (!results || !results.text_answers?.length)" rounded="xl" elevation="0" border
      class="mb-6">
      <v-card-text class="pa-8 text-center">
        <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-comment-text-outline-off</v-icon>
        <div class="text-h6 font-weight-medium text-grey-darken-1 mb-2">Keine Freitextantworten</div>
        <div class="text-body-2 text-grey">Es wurden noch keine freien Antworten zu den Fragen abgegeben.</div>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  name: 'TextAnswers',

  data() {
    return {
      loading: false,
      loggingOut: false,
      error: null,
      results: null,
      filterBeruf: null,
    }
  },

  computed: {
    berufeOptions() {
      if (!this.results) return []
      return Object.keys(this.results.meta.by_beruf || {})
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
        const { data } = await axios.get('/api/survey/results', {
          withCredentials: true,
          params,
        })
        this.results = data
      } catch (err) {
        if (err.response?.status === 401 || err.response?.status === 403) {
          this.$router.push('/')
        } else {
          this.error = 'Fehler beim Laden der Freitextantworten. Bitte versuche es erneut.'
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
        // ignore
      } finally {
        this.loggingOut = false
        this.$router.push('/')
      }
    },
  },
}
</script>
