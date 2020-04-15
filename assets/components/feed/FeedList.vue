<template>
    <v-responsive class="overflow-y-auto" min-height="300">
        <v-expansion-panels popout focusable>
            <LazyHydrate when-visible class="feednewsComponent" v-for="(news, i) in newsfeed" :key="i">
                <app-feed-news :news="news"/>
            </LazyHydrate>
        </v-expansion-panels>
    </v-responsive>
</template>

<script>
import FeedNews from './FeedNews'
import LazyHydrate from 'vue-lazy-hydration';

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
            if (isIntersecting && this.offset % 50 == 0) {
                this.offset = this.newsfeed.length
                this.$store.dispatch('FETCH_NEWSFEED', this.newsfeed.length)
                    .then(
                        this.feedListKey += 1
                    )
            }
        }
    },

    components: {
        appFeedNews: FeedNews,
        LazyHydrate
    },

    async mounted () {
        this.$store.dispatch('FETCH_NEWSFEED')
    }
}
</script>

<style lang="scss" scoped>
.feednewsComponent {
    width: 100%;
}
</style>
