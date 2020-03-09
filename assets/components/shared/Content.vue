<template>
    <v-app id="main">
        <app-sidebar></app-sidebar>
        <router-view></router-view>
    </v-app>
</template>

<script>
    import Sidebar from './Sidebar.vue';

    export default {
        props: {
            source: String,
        },

        components: {
            appSidebar: Sidebar,
        },

        methods: {
            fetchUser() {
                this.$store.dispatch('fetchUser')
            },

            fetchNews() {
                this.$store.dispatch('fetchNewsfeed')
            }
        },

        created () {
            this.$vuetify.theme.dark = true
        },

        mounted() {
            if (this.$store.getters.user == null) this.fetchUser()
            else this.fetchNewsfeed()
        }
    }
</script>
