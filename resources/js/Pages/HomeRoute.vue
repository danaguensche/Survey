<template>
    <div>
        <h2>HOME</h2>
        <router-link to="/test"> Take me to Test page </router-link>
        <button @click.prevent="tiggerEndpoint">Trigger Endpoint</button>
        <p v-if="response">{{ response.data }}</p>
        <p v-else>No response yet.</p>
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