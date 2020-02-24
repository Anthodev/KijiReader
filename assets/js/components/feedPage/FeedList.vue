<template>
    <div id="feedList">
        <app-feed-news v-for="(newsFeed, i) in feedNewsList" :key="i"></app-feed-news>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

import FeedNews from './FeedNews'

export default {
    data() {
        return {
            feedNewsList: []
        }
    },

    methods: {
        ...mapGetters({

        }),
        ...mapActions({

        })
    },

    components: {
        appFeedNews: FeedNews
    }
}
</script>

<style scoped>

</style>
