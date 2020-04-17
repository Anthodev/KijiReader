<template>
  <v-row>
    <app-feed-bar />
    <app-feed-list />
  </v-row>
</template>

<script>
export default {
  layout: 'default',

  components: {
    appFeedBar: () => import('../components/feed/FeedBar'),
    appFeedList: () => import('../components/feed/FeedList'),
  },

  async mounted() {
    this.$store.dispatch('FETCH_NEWSFEED').then(() => {
        this.$store.dispatch('SET_LOADING_STATE', {
          loading: false,
          type: ""
        })
      })
  }
}
</script>
