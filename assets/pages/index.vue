<template>
    <v-row>
        <app-feed-bar />
        <app-feed-list />
    </v-row>
</template>

<script>
import FeedBar from '../components/feed/FeedBar'
import FeedList from '../components/feed/FeedList'

export default {
    layout: 'default',

    methods: {
        fetchNews() {
            this.$store.dispatch('FETCH_NEWSFEED')
        }
    },

    components: {
        appFeedBar: FeedBar,
        appFeedList: FeedList,
    }
}
</script>
