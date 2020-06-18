<template>
  <v-skeleton-loader
    class="my-auto mx-auto"
    :loading="dataLoading"
    transition-group="fade-transition"
    width="100%"
    type="list-item, list-item, list-item, list-item, list-item"
    v-if="filteredNewsfeed.length > 0"
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
    filteredNewsfeed() {
      return this.$store.getters.filteredNewsfeed
    }
  },

  watch: {
    filteredNewsfeed (newFilteredNewsfeed, oldFilteredNewsfeed) {
      if (newFilteredNewsfeed) {
        $nuxt.refresh()
        this.dataLoading = false
      }
    },
  },

  components: {
    appFeedBar: () => import('../components/feed/FeedBar'),
    appFeedList: () => import('../components/feed/FeedList'),
    appFeedAdd: () => import('../components/shared/FeedAdd'),
  },

  beforeRouteEnter (to, from, next) {
    if (to != from && (from != '/signin' || from != '/signup')) {
      next(vm => {
        vm.dataLoading = true
      })
    }

    next(vm => {
      vm.dataLoading = false
    })
  },

  async mounted() {
    this.$store.dispatch('SET_LOADING_STATE', {
      loading: false,
      type: ""
    })

    this.dataLoading = false
  },
}
</script>
