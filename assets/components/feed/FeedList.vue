<template>
    <div>
      <p v-if="$fetchState.pending">Fetching newsfeed...</p>
      <app-news-card v-for="(news, $index) in items" :key="$index" :news="news" />
      <infinite-loading v-if="items.length" spinner="spiral" @infinite="loadMoreNews" />
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'

export default {
  data() {
    return {
      offset: 0,
      items: [],
      id: 0
    }
  },

  computed: {
    newsfeed () {
      return this.$store.getters.newsfeed
    }
  },

  watch: {
    newsfeed (newNewsfeed, oldnewsFeed) {
      $nuxt.refresh()
    }
  },

  methods: {
    async loadMoreNews($state) {      
      const result = await this.$store.dispatch('FETCH_NEWSFEED', this.offset)

      if (result.length > 1) {
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
    this.offset = 0
    
    const result = await this.$store.dispatch('FETCH_NEWSFEED', {
      offset: this.offset,
      id: this.id
    })

    this.offset = result.length
    this.items = result

    return result
  },

  mounted() {
    if (this.$route.params.id != null) this.id = this.$route.params.id
    else this.id = 0
  }
}
</script>
