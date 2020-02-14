<template>
    <div id="feedPane">
        <app-feed-bar></app-feed-bar>
        <div id="feedList">

        </div>
    </div>
</template>

<script>
import FeedBar from './FeedBar';

export default {
    components: {
        appFeedBar: FeedBar,
    }
}
</script>

<style>
#feedList {
    box-shadow: 0 0 1em #2f2f2f;
}
</style>
