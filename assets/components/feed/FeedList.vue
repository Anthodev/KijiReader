<template>
    <v-responsive class="overflow-y-auto" min-height="300">
        <v-expansion-panels popout focusable>
            <app-feed-news v-for="(news, i) in newsfeed" :key="i" :news="news" />
        </v-expansion-panels>
    </v-responsive>
</template>

<script>
export default {
    data() {
        return {
            totalNewsfeed: 0,
            offset: 0,
        }
    },

    computed: {
        newsfeed () {
            return this.$store.getters.newsfeed
        }
    },

    components: {
        appFeedNews: () => import('./FeedNews')
    }
}
</script>
