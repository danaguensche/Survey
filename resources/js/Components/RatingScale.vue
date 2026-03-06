<template>
  <div>
      <p v-if="label" class="text-body-2 mb-2">{{ label }}</p>
      <v-btn-toggle v-model="selected" mandatory color="primary" class="flex-wrap w-100">
          <v-btn
              v-for="n in scale"
              :key="n"
              :value="n"
              variant="outlined"
              size="small"
              style="flex: 1"
          >
              {{ n }}
          </v-btn>
      </v-btn-toggle>
      <div class="d-flex justify-space-between text-caption text-grey mt-1">
          <span>{{ lowLabel }}</span>
          <span>{{ highLabel }}</span>
      </div>
  </div>
</template>

<script>
export default {
  name: 'RatingScale',

  props: {
      modelValue: { type: Number, default: null },
      label: { type: String, default: '' },
      lowLabel: { type: String, default: 'trifft voll zu' },
      highLabel: { type: String, default: 'trifft überhaupt nicht zu' },
      scale: { type: Number, default: 6 }
  },

  emits: ['update:modelValue'],

  computed: {
      selected: {
          get() { return this.modelValue },
          set(val) { this.$emit('update:modelValue', val) }
      }
  }
}
</script>