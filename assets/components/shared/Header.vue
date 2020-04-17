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
                this.$store.dispatch('SET_DRAWER_STATUS')
            }
        }
    },

    methods: {
        setDrawerStatus () {
            this.$store.dispatch('SET_DRAWER_STATUS')
        },

        onLogout() {
            this.$store.dispatch('LOGOUT')
        }
    },

    components: {
        appFeedAdd: FeedAdd
    },
}
</script>

<style scoped>
    
</style>
