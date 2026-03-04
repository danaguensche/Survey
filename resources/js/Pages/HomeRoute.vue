<template>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Zufriedenheitsumfrage für Auszubildende der Diakonie
            am Campus</h1>
        <p class="text-gray-600">Ihre Meinung ist uns wichtig! Bitte nehmen Sie sich 5 Minuten Zeit, um diese
            anonyme Umfrage auszufüllen.</p>
        <p>Bewertung der Maßnahme im aktuellen Ausbildungsjahr</p>
        <div class="progress-bar mt-6">
            <div class="progress" id="progressBar" style="width: 0%"></div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import { ref } from 'vue';

export default {
    name: 'HomeRoute',
    methods: {
        tiggerEndpoint() {
            axios.get('/api/test-me')
                .then(response => {
                    this.response = response;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
    },
};
</script>