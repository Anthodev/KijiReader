<template>
    <v-row>
        <v-responsive>
            <v-lazy>
                <v-expansion-panels :key="feedListKey" focusable>
                    <app-feed-news v-for="(news, i) in newsfeed" :key="i" :news="news"></app-feed-news>
                </v-expansion-panels>
            </v-lazy>
        </v-responsive>
    </v-row>
</template>

<script>
import FeedNews from './FeedNews'

export default {
    data() {
        return {
            feedListKey: 0,
            totalNewsfeed: 0,
            offset: 0
        }
    },

    computed: {
        newsfeed () {
            this.feedListKey += 1
            return this.$store.getters.newsfeed
        }
    },

    components: {
        appFeedNews: FeedNews
    },

    async mounted () {
        this.$store.dispatch('fetchNewsfeed')
    }
}
</script>

<style scoped>

</style>
