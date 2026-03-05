<template>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 mb-8" data-aos="fade-up">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Zufriedenheitsumfrage für Auszubildende der Diakonie
                    am Campus</h1>
                <p class="text-gray-600">Ihre Meinung ist uns wichtig! Bitte nehmen Sie sich 5 Minuten Zeit, um diese
                    anonyme Umfrage auszufüllen.</p>
                <p>Bewertung der Maßnahme im aktuellen Ausbildungsjahr</p>
                <router-link to="/survey"
                    class="inline-block mt-4 px-6 py-3 bg-blue-600 text-black rounded-lg hover:bg-blue-700 transition">
                    Zur Umfrage
                </router-link>
                <div class="progress-bar mt-6">
                    <div class="progress" id="progressBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
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