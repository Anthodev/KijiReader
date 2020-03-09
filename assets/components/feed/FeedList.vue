<template>
    <v-responsive class="overflow-y-auto">
        <v-lazy :options="{ threshold: .1 }">
            <v-expansion-panels :key="feedListKey" focusable>
                <app-feed-news v-for="(news, i) in newsfeed" :key="i" :news="news"></app-feed-news>
                <v-row v-intersect.quiet="onBottomFeed" id="bottom"></v-row>
            </v-expansion-panels>
        </v-lazy>
    </v-responsive>
</template>

<script>
import FeedNews from './FeedNews'

export default {
    data() {
        return {
            feedListKey: 0,
            totalNewsfeed: 0,
            offset: 0,
        }
    },

    computed: {
        newsfeed () {
            this.feedListKey += 1
            return this.$store.getters.newsfeed
        }
    },

    methods: {
        onBottomFeed(entries, observer, isIntersecting) {
            if (isIntersecting && this.offset % 25 == 0) {
                this.offset = this.newsfeed.length
                this.$store.dispatch('fetchNewsfeed', this.newsfeed.length)
                    .then(
                        this.feedListKey += 1
                    )
            }
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
