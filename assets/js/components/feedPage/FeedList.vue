<template>
    <v-row>
        <v-expansion-panels focusable>
            <app-feed-news v-for="(news, i) in newsfeed" :key="i" :news="news"></app-feed-news>
        </v-expansion-panels>
    </v-row>
</template>

<script>
import FeedNews from './FeedNews'

export default {
    data() {
        return {
            
        }
    },

    computed: {
        newsfeed () {
            return this.$store.getters.newsfeed
        }
    },

    methods: {

    },

    components: {
        appFeedNews: FeedNews
    },

    created () {
        this.$store.dispatch('fetchNewsfeed')
    }
}
</script>

<style scoped>

</style>
