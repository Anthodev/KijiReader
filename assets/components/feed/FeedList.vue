<template>
    <div>
      <app-news-card v-for="(news, $index) in items" :key="$index" :news="news" />
      <infinite-loading v-if="items.length > 0" spinner="spiral" @infinite="loadMoreNews" />
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'

export default {
  data() {
    return {
      offset: 0,
      items: [],
      id: null,
      dataLoading: true
    }
  },

  computed: {
    newsfeed () {
      return this.$store.getters.newsfeed
    },

    refreshStatus () {
      return this.$store.getters.refreshStatus
    }
  },

  watch: {
    refreshStatus (newRefreshStatus, oldRefreshStatus) {
      if (newRefreshStatus) {
        $nuxt.refresh()
        this.$store.dispatch('SET_REFRESH_STATUS', false)
      }
    },

    items: function() {
      if (this.items.length == 0) this.dataLoading = true
      else this.dataLoading = false
    }
  },

  methods: {
    async loadMoreNews($state) {      
      const result = await this.$store.dispatch('FETCH_NEWSFEED', {
        offset: this.offset,
        id: this.id
      })

      if (result.length > 0) {
        result.forEach((item) => this.items.push(item))
        this.offset += result.length
        $state.loaded()
      } else $state.complete()
    },
  },

  components: {
    appNewsCard: () => import('./NewsCard'),
    InfiniteLoading
  },

  async fetch() {
    this.items.length = 0

    while (this.items.length == 0 && this.newsfeed.length > 0) {
      this.items = this.newsfeed
    }

    this.offset = this.items.length

    if (this.$route.params.id == undefined) this.id = this.$route.params.id
    else this.id = 0

    this.dataLoading = false

    return result
  },
}
</script>
