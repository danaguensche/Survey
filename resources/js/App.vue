<template>
    <router-view v-slot="{ Component, route }">
        <div :key="route.name">
            <Component :is="Component" />
        </div>
    </router-view>
</template>

<script>
import { mapState, mapActions } from 'pinia'
import { useAuthStore } from '../stores/auth'
export default {
    computed: {
        ...mapState(useAuthStore, ['user']),
    },
    methods: {
        ...mapActions(useAuthStore, ['checkAuth']),
    },
    async mounted() {
        await this.checkAuth()
        if (!this.user) {
            this.$router.push('/')
        }
    }
}
</script>