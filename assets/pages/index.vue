<template>
  <v-skeleton-loader
    class="my-auto mx-auto"
    :loading="dataLoading"
    transition-group="fade-transition"
    width="100%"
    type="list-item, list-item, list-item, list-item, list-item"
    v-if="newsfeed.length > 0"
  >
    <v-row>
      <app-feed-bar class="col-12 ml-n1 mb-n2" />
      <app-feed-list class="col-12" />
    </v-row>
  </v-skeleton-loader>
  <v-row v-else>
    <app-feed-add />
  </v-row>
</template>

<script>
export default {
  layout: 'default',

  data() {
    return {
      dataLoading: true
    }
  },

  computed: {
    newsfeed() {
      return this.$store.getters.newsfeed
    }
  },

  components: {
    appFeedBar: () => import('../components/feed/FeedBar'),
    appFeedList: () => import('../components/feed/FeedList'),
    appFeedAdd: () => import('../components/shared/FeedAdd'),
  },

  async mounted() {
    this.dataLoading = true
    this.$store.dispatch('FETCH_NEWSFEED', {
      offset: 0,
      id: this.$route.params.id
    }).then(() => {
      this.$store.dispatch('SET_LOADING_STATE', {
        loading: false,
        type: ""
      })

      this.dataLoading = false
    })
    
    this.$store.dispatch('FETCH_FEEDS')
  }
}
</script>
