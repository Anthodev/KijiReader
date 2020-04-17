<template>
    <div id="sidebar">
        <v-navigation-drawer v-model="drawer" width="12.5rem" app clipped>
            <v-list>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>mdi-settings</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Settings</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                    <v-btn v-if="checkAuth" @click.native="onLogout" block>Logout</v-btn>
                </div>
            </template>
        </v-navigation-drawer>
        
        <!-- <div id="sidebarMenu">
            <nuxt-link :to="{ name: 'home' }" class="menuElement"><font-awesome-icon :icon="['fas', 'home']" /> Home</nuxt-link>
        </div> -->
    </div>
</template>

<script>
import FeedAdd from './FeedAdd'

export default {
    computed: {
        checkAuth() {
            return this.$store.getters.isAuthenticated
        },

        drawer: {
                get () {
                    return this.$store.getters.drawer
                },

                set() {}
            }
    },

    methods: {
        onLogout() {
            this.$store.dispatch('LOGOUT')
        }
    },

    components: {
        appFeedAdd: FeedAdd
    }
}
</script>

<style scoped>
/* #sidebar {
    height: 78vh;
    padding: 1rem 2rem 1rem 1rem;
    background-color: #121212;
    border-radius: 0.5em;
}

.menuElement {
    padding-left: 1rem;
} */
</style>
