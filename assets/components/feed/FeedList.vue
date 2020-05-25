<template>
    <div>
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
      limit: 50,
      items: [],
      busy: false
    }
  },

  computed: {
    newsfeed () {
      return this.$store.getters.newsfeed
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
    const result = await this.$store.dispatch('FETCH_NEWSFEED', this.offset)

    this.offset = result.length
    this.items = result
  },
}
</script>
