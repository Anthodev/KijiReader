<template>
    <v-container fluid>
        <app-feed-bar />
        <app-feed-list />
    </v-container>
</template>

<script>
import FeedBar from '../components/feed/FeedBar'
import FeedList from '../components/feed/FeedList'

export default {
    components: {
        appFeedBar: FeedBar,
        appFeedList: FeedList,
    }
}
</script>
