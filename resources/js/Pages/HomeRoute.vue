<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Willkommen zum Ausbildungsumfrage</h1>
        <p class="mb-4">Diese Umfrage richtet sich an Auszubildende in verschiedenen Berufen, um Einblicke in ihre Erfahrungen und Meinungen zu gewinnen. Ihre Teilnahme ist freiwillig und anonym.</p>
        <p class="mb-4">Bitte klicken Sie auf den Button unten, um zur Umfrage zu gelangen:</p>
        <a href="/survey" class="inline-block bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition">Zur Umfrage</a>
    </div>
</template>

<script>

import axios from 'axios';
import { ref } from 'vue';

export default {
    name: 'HomeRoute',
    methods: {

    },
};
</script>