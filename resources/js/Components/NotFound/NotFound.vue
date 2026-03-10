<template>
    <v-container class="d-flex align-center justify-center" style="min-height: 100vh;">
        <v-card rounded="xl" border elevation="0" class="pa-6 text-center h-100">

            <v-row justify="center">
                <v-col cols="12" sm="8" md="6" class="text-center">
                    <h1 class="text-h1 font-weight-bold text-primary mb-2 text-headline-large">404</h1>

                    <p class="text-h5 font-weight-medium text-medium-emphasis mb-2">
                        Seite nicht gefunden
                    </p>

                    <p class="text-body-1 text-disabled mb-8">
                        Die Seite, die du suchst, existiert nicht oder wurde verschoben.
                    </p>

                    <v-btn color="primary" variant="elevated" size="large" rounded="lg" prepend-icon="mdi-home"
                        @click="$router.push('/')">
                        Zur Startseite
                    </v-btn>
                </v-col>

            </v-row>
        </v-card>

    </v-container>
</template>

<script>
export default {
    name: 'NotFound',
}
</script>