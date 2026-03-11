<template>
    <v-dialog v-model="dialog.show" max-width="480" persistent>
        <v-card rounded="xl" elevation="0" border>
            <v-card-text class="pa-8 text-center">
                <h3 class="font-weight-bold mb-2">{{ dialog.title }}</h3>
                <p class="text-body-2 text-medium-emphasis">{{ dialog.message }}</p>
            </v-card-text>
            <v-divider />
            <v-card-actions class="pa-4 justify-center">
                <v-btn :color="dialog.success ? 'primary' : 'error'" variant="tonal" rounded="lg" size="large"
                    @click="close">
                    {{ dialog.success ? 'Schließen' : 'Erneut versuchen' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: 'SurveyDialog',

    data: () => ({
        dialog: {
            show: false,
            success: true,
            title: '',
            message: '',
        },
    }),

    methods: {
        open(success, title, message) {
            this.dialog = { show: true, success, title, message }
        },
        close() {
            this.dialog.show = false
            this.$emit('close', this.dialog.success)
        },
    },
}
</script>
