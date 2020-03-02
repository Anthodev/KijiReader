<template>
    <div id="main" class="row">
        <div class="col-2">
            <app-sidebar></app-sidebar>
        </div>
        <div id="content" class="col-10">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Sidebar from './shared/Sidebar.vue';
    // import FeedPane from '../feedPage/FeedPane.vue';

    export default {
        data() {
            return {
                
            };
        },

        components: {
            appSidebar: Sidebar,
            // appFeedPane: FeedPane
        },

        methods: {
            fetchUser() {
                this.$store.dispatch('fetchUser')
            }
        },

        mounted() {
            if (this.$store.user === null) this.fetchUser()
        }
    }
</script>

<style scoped>
#main {
    margin: 5rem 0 5rem 0;
}

#content {
    padding: 1rem 2rem 1rem 1rem;
    border-radius: 1em;
}
</style>
