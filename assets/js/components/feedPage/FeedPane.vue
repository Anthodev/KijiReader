<template>
    <div id="feedPane">
        <app-feed-bar></app-feed-bar>
        <app-feed-list></app-feed-list>
    </div>
</template>

<script>
import FeedBar from './FeedBar'
import FeedList from './FeedList'

export default {
    components: {
        appFeedBar: FeedBar,
        appFeedList: FeedList,
    }
}
</script>

<style>

</style>
