<template>
    <v-app-bar app clipped-left>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title>KijiReader</v-toolbar-title>
        <v-spacer />
        <app-feed-add />
    </v-app-bar>
</template>

<script>
import FeedAdd from './FeedAdd'

export default {
    computed: {
        checkAuth() {
            return this.$store.getters.isAuthenticated
        },

        user () {
            return !this.$store.getters.user ? false : this.$store.getters.user.username
        },

        drawer: {
            get () {
                return this.$store.getters.drawer
            },

            set () {
                this.$store.dispatch('setDrawerStatus')
            }
        }
    },

    methods: {
        setDrawerStatus () {
            this.$store.dispatch('setDrawerStatus')
        },

        onLogout() {
            this.$store.dispatch('logout')
        }
    },

    components: {
        appFeedAdd: FeedAdd
    },

    created () {
        this.$vuetify.theme.dark = true
    },
}
</script>

<style scoped>
    
</style>
